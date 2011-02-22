/* 
* Name: Desktop
* Desc: Static class representing the area where widgetwindows are opened.
* Last update: 3/2-2011 by Dennis Sangmo
*/

Desktop = {
	// set this variable to the id of the main desktop in the view
	_desktop_div_id: 'desktop',

	// The main widget array
	_widgetArray : new Array(),
	_errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
	
	// Message properties
	message_current_position: -100,
	message_start_position: -100, // message_current_position will be set to this value after completion
	message_timer: null,
	message_speed: 100,
	message_tick: 20,
	message_width: 500, // also in css
  
	/*
	* --------------------------------------------------------------------------------------------------
	* MAIN DESKTOPFUNCTIONS
	* --------------------------------------------------------------------------------------------------
	*/
	
	// Opens a widget and creates its widget-object
	open_widget: function(widgetCallback, widgetIconId, wObject, project_widget_id, last_position) {
		// which state?
		var state = $('#'+widgetIconId).attr('state');
		if ( state == "" )
		{
			
			// Id-check
			var pwID;
			if((pwID = parseInt(project_widget_id)) == false){
				return;
			}
			
			var newX = 30, newY = 15, newH = 450, newW = 600;
			
			// use last position from database if no override?
			if (last_position != undefined) {
				newX = last_position.last_x;      
				newY = last_position.last_y;
			} 
			
			// use last position from database? (default value is 0 from database)
			if ( last_position != undefined && (last_position.width != 0 && last_position.height != 0) ) {
				newW = last_position.width;      
				newH = last_position.height;
			}
			
			// Setting up default widgetoptions
			var options = {
				// change theese as needed
				title: "",
				widgetIconId: widgetIconId,
				width: newW,
				height: newH,
				x: newX,
				y: newY,
				allowSettings: true,
				bookmarkable: false,
				
				// add events for close
				onMinimize: function(){ Desktop.close_widget(pwID); },
				onClose: function(){ Desktop.reset_widget(widgetIconId); Desktop.save_position(pwID); },
				
				// add events for updating status and position
				afterDrag: function() { Desktop.update_position(pwID); },
				afterCascade: function() { Desktop.update_position(pwID, true); },
				afterMaximize: function() { Desktop.update_position(pwID, true); }, 
				afterResize: function() { Desktop.update_position(pwID); },
				
				// set boundries for window
				checkBoundary: true,
				maxWidth: $('#'+this._desktop_div_id).width(),
				maxHeight: $('#'+this._desktop_div_id).height()
			};
			
			// create window
			this._widgetArray.push(new window[wObject](pwID, options));
			var pos = this._widgetArray.length - 1;
			this._widgetArray[pos].index();
			
			// use last position (maximized) from database if no override?
			if ( last_position != undefined && last_position.is_maximized) {
				this._widgetArray[pos].wnd.maximize();    
			}
			
			// set status as open for widget
			Desktop.update_position(pwID);
			
			// set state as open and transparency for icon to 20%
			$('#'+widgetIconId).attr('state', 'open');
			$('#'+widgetIconId).css({ 'opacity':'0.2', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"', 'filter':'alpha(opacity=20)' });

			// update desktop size
			this.updateContentSize();
		}
		
	},


	// Callback for close
	close_widget: function(pwID) {
		var pos = Desktop.findWidgetById(pwID);
		// close widget
		this._widgetArray[pos].closeWidget();
		// reset icon
		reset_widget(this._widgetArray[pos].widgetIconId);
		this._widgetArray.splice(pos, 1);
	},
	
	/**
	 * This function will execute a function inside a widget object.
	 * @param target The instance id of the widget to call the function or the domelement that is invoking it.
	 */
	callWidgetFunction:function(target, func) {
		
		//Instance_id
		var instance_id, 
			args = Array().slice.call( arguments, 2 );
		
		// If it's an int we can assume that it's a project widget id
		if(parseInt(target)) {
			instance_id = target;
		} else {
			args.reverse();
			args.push(target);
			args.reverse();
			// We find the closest parent that begins with "widget_" and assign its id to the variable widgetid.
			var str_id;
			while(!parseInt(instance_id)) {
				target = $(target).parents('div[id^="widget_"]');
				str_id = target.attr("id");
				instance_id = str_id.split('_')[1];
			}
		}
		
		var pos = Desktop.findWidgetById(instance_id);
		
		if(args.length == 1) {
			return this._widgetArray[pos][func](args[0]);
		} else {
			return this._widgetArray[pos][func](args);
		}
		
       
	},
	
	/*
	* --------------------------------------------------------------------------------------------------
	* MESSAGES
	* --------------------------------------------------------------------------------------------------
	*/
	
	// function for widgets to display an ok-message (green)
	show_message: function(message) {
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>');
		$('#message').css('top',Desktop.message_current_position+'px');
		$('#message').addClass('ok');
		
		$('#fullpage_overlay').click(function(){ Desktop.close_message(); $('#message').removeClass('ok'); });
		$('#message').click(function(){ Desktop.close_message(); $('#message').removeClass('ok'); }); 
		
		Desktop.start_message_animate();
	},

	// function for widgets to display an error-message (red)
	show_errormessage: function(message) {
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>'); 
		$('#message').css('top',Desktop.message_current_position+'px');
		$('#message').addClass('error');
		
		$('#fullpage_overlay').click(function(){ Desktop.close_message(); $('#message').removeClass('error'); });
		$('#message').click(function(){ Desktop.close_message(); $('#message').removeClass('error'); }); 
		
		Desktop.start_message_animate();
	},

	// common function to set timer and start animate
	start_message_animate: function() {
		var maxWidth = $('#'+this._desktop_div_id).width();
		var centerPosition = (maxWidth/2)-(Desktop.message_width/2);
		$('#message').css('left',centerPosition+'px');
		$('#message').css('top',Desktop.message_start_position+'px');
		
		$('#fullpage_overlay').show(); 
		
		Desktop.message_timer = setInterval('Desktop.message_animate()', Desktop.message_speed);
		$('#message').fadeIn(Desktop.message_speed);
	},
	
	// Will display a loading image in the widget with the id
	show_ajax_loader_in_widget: function(pwID) {
		var pos = Desktop.findWidgetById(pwID);
		Desktop._widgetArray[pos].show_ajax_loader();
	},
	
	// Will display an ajax error in the widget with the id
	show_ajax_error_in_widget: function(loadURL, pwID) {
		var pos = Desktop.findWidgetById(pwID);
		this._widgetArray[pos].show_ajax_error(loadURL, Desktop._errorIcon);
	},

	// callback function for timer
	message_animate: function() {
		if (Desktop.message_current_position<0)
		{
			Desktop.message_current_position += Desktop.message_tick;
			$('#message').css('top',Desktop.message_current_position+'px');    
		}
		else
		{
			Desktop.reset_message();
		}
	},

	// hide div for message and overlay
	close_message: function() {
		$('#fullpage_overlay').hide();
		$('#message').hide();    
	},

	// reset timer and position for a new round
	reset_message: function() {
		clearInterval(Desktop.message_timer);    
		Desktop.message_timer = null;
		Desktop.message_current_position = Desktop.message_start_position;  
	},
	
	/*
	* --------------------------------------------------------------------------------------------------
	* SETTINGS 
	* --------------------------------------------------------------------------------------------------
	*/
	
	// Depending of the settingstate will it open or close the window.
	openSettingsWindow: function(pwID) {
		var pos = Desktop.findWidgetById(pwID);
		if(this._widgetArray[pos].getSettingsState() == false) {
			ajaxRequests.load_full(SITE_URL+'/widget_settings/GetProjectWidgetSettings/'+pwID, "Desktop.openSettingsWindowSuccess", "Desktop.show_ajax_error_in_widget", pwID);
		} else {
			this._widgetArray[pos].closeSettings();
		}
	},
	
	// Success function of open settings.
	openSettingsWindowSuccess: function(data, pwID) {
		var pos = Desktop.findWidgetById(pwID);
		this._widgetArray[pos].setSettingsContent(data);
		$('#' + pwID + '_settings').validate();
		$( ".date" ).datepicker();
	},
	
	// Saves the settingswindow
	saveSettingsForm: function(pwID) {
		if($('#' + pwID + '_settings').valid()) {
			
			var settings = $('#' + pwID + '_settings').find('input');
			
			var formArray = new Array()
			for(var i = 0; i < settings.length; i++) {
				var val = [];
				val['name'] = settings[i]['name'];
				if(settings[i]['type'] == "checkbox") {
					val['value'] = $(settings[i]).attr('checked') ? 'true' : 'false';
				} else {
					val['value'] = settings[i]['value'];
				}
				formArray.push(val);
			}
			var id = [];
			id['name'] = 'Project_widgets_id';
			id['value'] = pwID;
			formArray.push(id);
			
			// Send if event is active for this widget. To avoid unnecessary database calls.
			var event = [];
			event['name'] = 'Event';
			if(this.settingsEvent.exist(pwID) > -1) {
				event['value'] = "true";
			} else {
				event['value'] = "false";
			}
			formArray.push(event);
			
			ajaxRequests.post_full(formArray, SITE_URL+'/widget_settings/SaveProjectWidgetSettings', "Desktop.ajaxReturnSuccess", "Desktop.ajaxReturnFail", pwID);
		}
		
		return false;
	},
	
	//called when when the post ajax request are success
	ajaxReturnSuccess: function(data, pwID) {
		var json;
		if(json = $.parseJSON(data)){
			// Everything went ok
			if(json.status == "ok") {
				Desktop.show_message(json.status_message);
				Desktop.settingsEvent.sendData(pwID, json.data);
				Desktop.openSettingsWindow(pwID);
			} else {
				Desktop.show_errormessage(json.status_message);
			}
		} else {
			Desktop.show_errormessage("A error has occurred, admins has been informed!");
		}
	},
	
	ajaxReturnFail: function(url){
		Desktop.show_errormessage("Error occurred while loading the url: " + url);
	},
	
	// Settings event that will send the saved setting to assigned function.
	settingsEvent : {
		functionArray : new Array(),
		
		// Adds a listener 
		addSettingsEventListener: function(pwID, funcName){
			if(typeof pwID != "number"){
			alert(typeof pwID);
				return false;
			}
			if(typeof funcName != "string"){
			alert(typeof funcName);
				return false;
			}
			
			var tmp = new Array();
			tmp['id'] = pwID;
			tmp['name'] = funcName;
			var pos = this.exist(pwID);
			if(pos == -1) {
				this.functionArray.push(tmp);
			} else {
				this.functionArray[pos] = tmp;
			}
			return true;
		},
		
		// Checks in the array if the listener exist, return the arrayposition
		exist: function(pwID){
			for(var i = 0; i < this.functionArray.length; i++){
				if(this.functionArray[i]['id'] == pwID){
					return i;
				}
			}
			return -1;
		},
		
		// Datasender, executed after the settinging are saved
		sendData: function(pwID, data) {
			var funcArray = Desktop.settingsEvent.functionArray;
			for(var i = 0; i < funcArray.length; i++) {
				if(funcArray[i]['id'] == pwID){
					Desktop.callWidgetFunction(pwID, funcArray[i]['name'], data);
				}
			}
		}
	},
	
	
	/*
	* --------------------------------------------------------------------------------------------------
	* DEBUG 
	* --------------------------------------------------------------------------------------------------
	*/
	
	// variable so debug is accessible for all widgets
    debug_win: null,

    // open and/or append a debug message
    log_message: function(message) {
        
        // does a window exist?
        if (Desktop.debug_win == null) {
            
            // create new window
         
            var options = {
                    title: 'Debug messages',
                    width: 400,
                    height: 525,
                    x: 30,
                    y: 15,
                    onClose: function() { Desktop.close_debug_window();  },
                    content: '<div id="debug_widget_messages"></div>'
            };
         
            Desktop.debug_win = $('#'+this._desktop_div_id).window(options);
        }        
            
        // get date + time for message        
        var d = new Date();
        var month = d.getMonth()+1; // function returns 0-11
        if (month<10) {
            month = '0'+month;
        }
        var day = d.getDate(); // returns 1-31
        if (day<10) {
            day = '0'+day;
        }
        var hour = d.getHours(); // returns 0-23
        if (hour<10) {
            hour = '0'+hour;
        }
        var minutes = d.getMinutes(); // returns 0-59
        if (minutes<10) {
            minutes = '0'+minutes;
        }                 
        var seconds = d.getSeconds(); // returns 0-59
        if (seconds<10) {
            seconds = '0'+seconds;
        } 
        var timestamp = d.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minutes+':'+seconds;
            
        // append message
        $('#debug_widget_messages').append('<p><span class="debug_timestamp">['+timestamp+']:</span> '+message+'</p>');
    },

    // callback to close a debug window
    close_debug_window: function() {
      Desktop.debug_win = null;  
    },
    
	
	/*
	* --------------------------------------------------------------------------------------------------
	* WIDGET POSITIONING
	* --------------------------------------------------------------------------------------------------
	*/
	
    // called on event afterDrag and afterMaximize and afterCascade and afterResize
    update_position: function(project_widget_id, onlyUpdateMaximize) {   
		var pos = Desktop.findWidgetById(project_widget_id);
       // get current status
       var window_status = Desktop.get_current_window_status(pos);
      
       // no data?
       if (window_status == null || window_status == false) {
           // quit; nothing to save
           return;
       }
       
       // this window is OPEN
       window_status.is_open = true;
       
       // prepare url and postdata
       var url = SITE_URL + '/widget_position/update';
	   
	   var postdata;
	   if ( onlyUpdateMaximize != undefined && onlyUpdateMaximize == true ) {
	   
			// only save for maximized			
			postdata = { 'is_open': window_status.is_open, 'is_maximized': window_status.is_maximized, 'project_widget_id': project_widget_id };
		
	   } else {
			postdata = { 'height': window_status.height, 'width': window_status.width, 'is_open': window_status.is_open, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top, 'project_widget_id': project_widget_id };
       
			// save new position to object
			this._widgetArray[pos].last_position = { 'height': window_status.height, 'width': window_status.width, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top };
		}
       
       // save to database
	   $.post(url, postdata);
    },
    
    // on close window; save last position to database
    save_position: function(project_widget_id) {
        var pos = Desktop.findWidgetById(project_widget_id);
       
       // get current status
       var window_status = Desktop.get_current_window_status(pos);
      
       // no data?
       if (window_status == null || window_status == false) {
           // quit; nothing to save
           return;
       }
       
       // this window is closed (event is onClose)
       window_status.is_open = false;
       
       // prepare url and postdata
       var url = SITE_URL + '/widget_position/save';
       var postdata = { 'height': window_status.height, 'width': window_status.width, 'is_open': window_status.is_open, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top, 'project_widget_id': project_widget_id };
       
       // save new position to object
       this._widgetArray[pos].last_position = { 'height': window_status.height, 'width': window_status.width, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top };
       
       // save to database
	   $.post(url, postdata);
        
    },
    
    // get status of current window (position etc)
    get_current_window_status: function(arrayPos) {
        
       if (typeof arrayPos != "number" || arrayPos == undefined) {
             // just return and do not update.. nothing selected
             return null;
       }
       
       // setup default values (will be replaced)
       var returnData = {
            'is_open': true,  // allways true
            'is_maximized': false,
            'offset': { 'top':0, 'left': 0 },
            'width': 0,
            'height': 0
       };
       
       var container = this._widgetArray[arrayPos].wnd.getContainer();
       
       // get desktop position (offset from window) 
	   var desktop_offset = $('#'+this._desktop_div_id).offset();
        
        // get current window status 
       returnData.is_maximized = this._widgetArray[arrayPos].wnd.isMaximized(); 
       returnData.offset = container.offset();
       
       // calcuate new offset for top
       returnData.offset.top = returnData.offset.top - desktop_offset.top;   
       
       // get width and height
       returnData.width = container.width();
       returnData.height = container.height(); 
              
       // return the data we got
       return returnData; 
    },

    updateContentSize: function() {
        var docHeight = $(document).height();
        var topBarHeight = $('#topbar').height();
        var wBarHeight = $('#widget_bar').height();
        var contentHeight;

        if($('#topbar').is(':visible') == false && $('#widget_bar').is(':visible') == false)
        {
            contentHeight = docHeight - 10; // 10 is for margins
        }
        else if($('#topbar').is(':visible') == false && $('#widget_bar').is(':visible') != false)
        {
            contentHeight = (docHeight - wBarHeight) - 10; // 10 is for margins
        }
        else if($('#widget_bar').is(':visible') == false)
        {
            contentHeight = (docHeight - topBarHeight) - 10; // 10 is for margins
        }
        else
        {
            contentHeight = ((docHeight - topBarHeight) - wBarHeight) - 10; // 10 is for margins
        }

        $('#'+this._desktop_div_id).css('height',contentHeight+'px');
    },
    
	/*
	* --------------------------------------------------------------------------------------------------
	* HELPER FUNCTIONS
	* --------------------------------------------------------------------------------------------------
	*/
	
	// Resets the widget icon in the widgetbar.
	reset_widget: function(widgetIconId) {
		// set state to none and set transparency to 100%
		$('#'+widgetIconId).attr('state', '');
		$('#'+widgetIconId).css({ 'opacity':'1.0', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"', 'filter':'alpha(opacity=100)' });
	},
	
	// Loop that will locat the arrayposition on a widget
	findWidgetById: function(id) {
		for(var i = 0; i < this._widgetArray.length; i++) {
			if(Desktop._widgetArray[i].id == id){
				return i;
			}
		}
	}
}


// ----------------------------------------------------------------

// fetches the current project_id
$(document).ready(function() {
    Desktop.currentProjectId = $("#"+Desktop._desktop_div_id).attr("pid");
});

// ----------------------------------------------------------------

// shorthand global function to wrap Desktop.log_message into log_message
function log_message(msg) {
    Desktop.log_message(msg);
}

// shorthand to dump a variable
function log_variable(msg, data) {
    if (msg != null && msg != '') {
        // write message + variable
        Desktop.log_message( msg+' '+$.dump(data) );    
    }
    else {
        // write only variable
        Desktop.log_message( $.dump(data) );    
    }
}


Function.prototype.Inherits = function( parent ) {
	this.prototype = new parent();
	this.prototype.constructor = this;
}



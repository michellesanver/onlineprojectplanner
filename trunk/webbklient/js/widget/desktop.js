
Desktop = {
	
	_widgetArray : new Array(),
	_errorIcon: BASE_URL+'images/backgrounds/erroricon.png', 
	selectedWindowId: null,
	currentProjectId: null,
	// Message properties
	message_current_position: -100,
	message_start_position: -100, // message_current_position will be set to this value after completion
	message_timer: null,
	message_speed: 100,
	message_tick: 20,
	message_width: 500, // also in css
  
	// Will open a new window and add it to the windowlist
	newWidgetWindow : function(project_widget_id, options, widgetIconId, partialContentClasses, last_position) {
	
		// set id
		Desktop.selectedWindowId = project_widget_id;
		
        // get position from object (new position has been saved)?
        if ( this._widgetArray[Desktop.selectedWindowId] != undefined && this._widgetArray[Desktop.selectedWindowId].last_position != undefined ) {
            
            // override parameter to function with saved position
            last_position = this._widgetArray[Desktop.selectedWindowId].last_position;
        }
        
		// add events for close
		options.onMinimize = function(){ Desktop.close_widget(widgetIconId); };
		options.onClose = function(){ Desktop.reset_widget(widgetIconId); Desktop.save_position(project_widget_id); };
        
        // add events for updating status and position
        options.afterDrag = function() { Desktop.update_position(project_widget_id); };
        options.afterCascade = function() { Desktop.update_position(project_widget_id, true); };
        options.afterMaximize = function() { Desktop.update_position(project_widget_id, true); }; 
        options.afterResize = function() { Desktop.update_position(project_widget_id); }; 
        
        // set boundries for window
		options.checkBoundary = true;
		options.maxWidth = $('#desktop').width();
		options.maxHeight = $('#desktop').height();
		if (options.bookmarkable == undefined )
		{
			options.bookmarkable = false;
		}
		
		if (options.allowSettings == undefined )
		{
			options.allowSettings = true;
		}
		
        // use last position from database if no override?
        if ( (options.x == undefined || options.y == undefined) && last_position != undefined) {
            options.x = last_position.last_x;      
            options.y = last_position.last_y;
        } 
        
        // use last position from database? (default value is 0 from database)
        if ( last_position != undefined && (last_position.width != 0 && last_position.height != 0) ) {
            options.width = last_position.width;      
            options.height = last_position.height;
        }
        
		// save partialContentClass if it is set
		var partialClasses = new Array();
		if (partialContentClasses != undefined)
		{
			if($.isArray(partialContentClasses)) {
				partialClasses = partialContentClasses;
			} else {
				partialClasses.push(partialContentClasses);
			}
		}
		
		// create window
		this._widgetArray[Desktop.selectedWindowId] = new Widget(Desktop.selectedWindowId, options, partialClasses);
		
        // use last position (maximized) from database if no override?
        if ( last_position != undefined && last_position.is_maximized  && options.maximize == undefined) {
            if (last_position.is_maximized == true) {
                this._widgetArray[Desktop.selectedWindowId].wnd.maximize();    
            }      
        } else if (options.maximize == true) {
            this._widgetArray[Desktop.selectedWindowId].wnd.maximize();                
        }
        
        // set status as open for widget
        Desktop.update_position(project_widget_id);
        
		// return new id
		return Desktop.selectedWindowId;
	},
	
	// Will transfer contentdata to the selected window
	setWidgetContent : function(data) {
		this._widgetArray[Desktop.selectedWindowId].setContent(data);
	},
	
	// sets the partial content in the selected widget
	setWidgetPartialContent : function(inClass, data) {
		this._widgetArray[Desktop.selectedWindowId].setPartialContent(inClass, data);
	},
	
	// --------------------------------------------------------------------------------------------------
	// open and close widgets in widget bar
	
	open_widget: function(widgetCallback, widgetIconId, wObject, project_widget_id, last_position)
	{
		// which state?
		var state = $('#'+widgetIconId).attr('state');
		if ( state == "" )
		{
			// no state!
            
			// run callback to open widget
            var widgetObj = eval(wObject);
            widgetObj.open(project_widget_id, widgetIconId, last_position);
			
			// set state as open and transparency for icon to 20%
			$('#'+widgetIconId).attr('state', 'open');
			$('#'+widgetIconId).css({ 'opacity':'0.2', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"', 'filter':'alpha(opacity=20)' });

                        // update desktop size

                        this.updateContentSize();
                    }
		
	},

	// callback for minimize
	close_widget: function(widgetIconId)
	{
		// close widget
        this._widgetArray[Desktop.selectedWindowId].closeWidget();
		// reset icon
		reset_widget(widgetIconId);
	},

	// callback for close
	reset_widget: function(widgetIconId)
	{
		// set state to none and set transparency to 100%
		$('#'+widgetIconId).attr('state', '');
		$('#'+widgetIconId).css({ 'opacity':'1.0', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"', 'filter':'alpha(opacity=100)' });
	},
	
	// function for widgets to display an ok-message (green)
	show_message: function(message)
	{
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>');
		$('#message').css('top',Desktop.message_current_position+'px');
		$('#message').addClass('ok');
		
		$('#fullpage_overlay').click(function(){ Desktop.close_message(); $('#message').removeClass('ok'); });
		$('#message').click(function(){ Desktop.close_message(); $('#message').removeClass('ok'); }); 
		
		Desktop.start_message_animate();
	},

	// function for widgets to display an error-message (red)
	show_errormessage: function(message)
	{
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>'); 
		$('#message').css('top',Desktop.message_current_position+'px');
		$('#message').addClass('error');
		
		$('#fullpage_overlay').click(function(){ Desktop.close_message(); $('#message').removeClass('error'); });
		$('#message').click(function(){ Desktop.close_message(); $('#message').removeClass('error'); }); 
		
		Desktop.start_message_animate();
	},

	// common function to set timer and start animate
	start_message_animate: function()
	{
		var maxWidth = $('#desktop').width();
		var centerPosition = (maxWidth/2)-(Desktop.message_width/2);
		$('#message').css('left',centerPosition+'px');
		$('#message').css('top',Desktop.message_start_position+'px');
		
		$('#fullpage_overlay').show(); 
		
		Desktop.message_timer = setInterval('Desktop.message_animate()', Desktop.message_speed);
		$('#message').fadeIn(Desktop.message_speed);
	},
	
	// Will display a loading image in the widget with the id
	show_ajax_loader_in_widget: function()
	{
		this._widgetArray[Desktop.selectedWindowId].show_ajax_loader();
	},
	// Will display an ajax error in the widget with the id
	show_ajax_error_in_widget: function(loadURL)
	{
		this._widgetArray[Desktop.selectedWindowId].show_ajax_error(loadURL, Desktop._errorIcon);
	},

	// callback function for timer
	message_animate: function()
	{
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
	close_message: function()
	{
		$('#fullpage_overlay').hide();
		$('#message').hide();    
	},

	// reset timer and position for a new round
	reset_message: function()
	{
		clearInterval(Desktop.message_timer);    
		Desktop.message_timer = null;
		Desktop.message_current_position = Desktop.message_start_position;  
	},
	
	// Depending of the settingstate will it open or close the window.
	openSettingsWindow: function()
	{
		if(this._widgetArray[Desktop.selectedWindowId].getSettingsState() == false) {
			ajaxRequests.load(SITE_URL+'/widget_settings/GetProjectWidgetSettings/'+Desktop.selectedWindowId, "Desktop.openSettingsWindowSuccess", "Desktop.ajaxSettingsWindowError", true);
		} else {
			this._widgetArray[Desktop.selectedWindowId].closeSettings();
		}
	},
	
	// Success function of open settings.
	openSettingsWindowSuccess: function(data)
	{
		this._widgetArray[Desktop.selectedWindowId].setSettingsContent(unescape(data));
		$('#' + Desktop.selectedWindowId + '_settings').validate();
		$( ".date" ).datepicker();
	},
	
	ajaxSettingsWindowError: function(loadURL)
	{
		Desktop.show_ajax_error_in_widget(loadURL);
	},
	
	// Saves the settingswindow
	saveSettingsForm: function()
	{
		if($('#' + Desktop.selectedWindowId + '_settings').valid()) {
			
			var settings = $('#' + Desktop.selectedWindowId + '_settings').find('input');
			
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
			id['value'] = Desktop.selectedWindowId;
			formArray.push(id);
			
			ajaxRequests.post(formArray, SITE_URL+'/widget_settings/SaveProjectWidgetSettings', "Desktop.saveSettingsWindowSuccess", "Desktop.ajaxSettingsWindowError", true);
		}
		
		return false;
	},
	
	//called when when the post ajax request are success
	saveSettingsWindowSuccess: function(data)
	{
		data = unescape(data);
		if(data == "true"){
			Desktop.show_message("The settings has been saved. To return please click the settingsbutton!");
		} else {
			Desktop.show_errormessage("The settings did not get saved.<br />" + data);
		}
	},
	
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
         
            Desktop.debug_win = $('#desktop').window(options);
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
    
    // called on event afterDrag and afterMaximize and afterCascade and afterResize
    update_position: function(project_widget_id, onlyUpdateMaximize) {   
        
       // get current status
       var window_status = Desktop.get_current_window_status();
      
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
			this._widgetArray[Desktop.selectedWindowId].last_position = { 'height': window_status.height, 'width': window_status.width, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top };
		}
       
       // save to database
       ajaxRequests.post(postdata, url, 'Desktop.update_position_callback_ok', 'Desktop.update_position_callback_error', true); 
         
    },
    
    update_position_callback_ok: function(data) { 
        // not used but a requirement for ajaxRequests
    },
    
    update_position_callback_error: function(data) { 
        // not used but a requirement for ajaxRequests
    },
    
    // on close window; save last position to database
    save_position: function(project_widget_id) {
       
       // get current status
       var window_status = Desktop.get_current_window_status();
      
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
       this._widgetArray[Desktop.selectedWindowId].last_position = { 'height': window_status.height, 'width': window_status.width, 'is_maximized': window_status.is_maximized, 'last_x': window_status.offset.left, 'last_y': window_status.offset.top };
       
       // save to database
       ajaxRequests.post(postdata, url, 'Desktop.save_position_callback_ok', 'Desktop.save_position_callback_error', true);  
        
    },
    
    save_position_callback_ok: function(data) {
        // not used but a requirement for ajaxRequests
    },

    save_position_callback_error: function(data) {
		// not used but a requirement for ajaxRequests
    },
    
    // get status of current window (position etc)
    get_current_window_status: function() {
        
       if (Desktop.selectedWindowId == undefined || Desktop.selectedWindowId == "" || Desktop.selectedWindowId == null) {
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
       
       var container = this._widgetArray[Desktop.selectedWindowId].wnd.getContainer();
       
       // get desktop position (offset from window) 
       var desktop_offset = container.parent().offset();
        
        // get current window status 
       returnData.is_maximized = this._widgetArray[Desktop.selectedWindowId].wnd.isMaximized(); 
       returnData.offset = container.offset();
       
       // calcuate new offset for top
       returnData.offset.top = returnData.offset.top - desktop_offset.top;   
       
       // get width and height
       returnData.width = container.width();
       returnData.height = container.height(); 
              
       // return the data we got
       return returnData; 
    },

    updateContentSize: function()
    {
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

        $('#desktop').css('height',contentHeight+'px');
    }
    
}


// ----------------------------------------------------------------

// fetches the current project_id
$(document).ready(function() {
    Desktop.currentProjectId = $("#desktop").attr("pid");
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




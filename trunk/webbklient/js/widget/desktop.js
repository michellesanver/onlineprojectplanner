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
	newWidgetWindow : function(project_widget_id, options, widgetIconId, partialContentClasses) {
	
		// set id
		Desktop.selectedWindowId = project_widget_id;
		
		// add more options
		options.onMinimize = function(){ Desktop.close_widget(widgetIconId); };
		options.onClose = function(){ Desktop.reset_widget(widgetIconId); };
		options.checkBoundary = true;
		options.maxWidth = $('#desktop').width();
		options.maxHeight = $('#desktop').height();
		if (options.bookmarkable == undefined )
		{
			options.bookmarkable = false;
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
	
	open_widget: function(widgetCallback, widgetIconId, wObject, project_widget_id)
	{
		// which state?
		var state = $('#'+widgetIconId).attr('state');
		if ( state == "" )
		{
			// no state!
			
			// run callback to open widget
			eval(wObject+'.open("'+project_widget_id+'", "'+widgetIconId+'")');
			
			// set state as open and transparency for icon to 20%
			$('#'+widgetIconId).attr('state', 'open');
			$('#'+widgetIconId).css({ 'opacity':'0.2', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"', 'filter':'alpha(opacity=20)' });
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
			ajaxRequests.load(SITE_URL+'/Widget_settings/GetProjectWidgetSettings/'+Desktop.selectedWindowId, "Desktop.openSettingsWindowSuccess", "Desktop.ajaxSettingsWindowError", true);
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
			
			ajaxRequests.post(formArray, SITE_URL+'/Widget_settings/SaveProjectWidgetSettings', "Desktop.saveSettingsWindowSuccess", "Desktop.ajaxSettingsWindowError", true);
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
    }

}

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




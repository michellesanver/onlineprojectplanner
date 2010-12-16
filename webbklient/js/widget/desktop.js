Desktop = {
	
	_widgetArray : new Array(),
	_errorIcon: BASE_URL+'images/backgrounds/erroricon.png', 
	selectedWindowId: null,
	// Message properties
	message_current_position: -100,
	message_start_position: -100, // message_current_position will be set to this value after completion
	message_timer: null,
	message_speed: 100,
	message_tick: 20,
	message_width: 500, // also in css
  
	
	newWidgetWindow : function(options, widgetIconId, partialContentClasses) {
	
		// set id
		Desktop.selectedWindowId = this._widgetArray.length+2;
		
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
	
	setWidgetContent : function(data) {
		this._widgetArray[Desktop.selectedWindowId].setContent(data);
	},
	
	// sets the partial content in the selected widget
	setWidgetPartialContent : function(inClass, data) {
		this._widgetArray[Desktop.selectedWindowId].setPartialContent(inClass, data);
	},
	
	// --------------------------------------------------------------------------------------------------
	// open and close widgets in widget bar
	
	open_widget: function(widgetCallback, widgetIconId, wObject)
	{
		// which state?
		var state = $('#'+widgetIconId).attr('state');
		if ( state == "" )
		{
			// no state!
			
			// run callback to open widget
			eval(wObject+'.open("'+widgetIconId+'")');
			
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
	
	openSettingsWindow: function()
	{
		this._widgetArray[Desktop.selectedWindowId].open_settings_Window();
	}
	
}

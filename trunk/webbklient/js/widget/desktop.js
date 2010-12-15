Desktop = {
	
	_widgetArray : new Array(),
	_errorIcon: BASE_URL+'images/backgrounds/erroricon.png', 
    
	newWidgetWindow : function(options, widgetIconId, partialContentClass) {
	
		// add more options
		options.onMinimize = function(){ Desktop.close_widget(widgetIconId, id); };
		options.onClose = function(){ Desktop.reset_widget(widgetIconId); };
		options.checkBoundary = true;
		options.maxWidth = $('#desktop').width();
		options.maxHeight = $('#desktop').height();
		if (options.bookmarkable == undefined )
		{
			options.bookmarkable = false;
		}
		
		// set id
		var id = this._widgetArray.length;
		// create window
		this._widgetArray.push(new Widget(id, options));
		
		return id;
	},
	
	setWidgetContent : function(id, data) {
		this._widgetArray[id].setContent(data);
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
	close_widget: function(widgetIconId, id)
	{
		// close widget
        this._widgetArray[id].closeWidget();
		
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
	
	
	// ---------------------------------------------------------------------------------------------------
	// messages:
	
	message_current_position: -100,
	message_start_position: -100, // message_current_position will be set to this value after completion
	message_timer: null,
	message_speed: 100,
	message_tick: 20,
	message_width: 500, // also in css

	// function for widgets to display an ok-message (green)
	show_message: function(message)
	{
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>');
		$('#message').css('top',message_current_position+'px');
		$('#message').addClass('ok');
		
		$('#fullpage_overlay').click(function(){ close_message(); $('#message').removeClass('ok'); });
		$('#message').click(function(){ close_message(); $('#message').removeClass('ok'); }); 
		
		start_message_animate();
	},

	// function for widgets to display an error-message (red)
	show_errormessage: function(message)
	{
		$('#message').html('<p>'+message+'</p>'+'<p>Click anywhere to close this message</p>'); 
		$('#message').css('top',message_current_position+'px');
		$('#message').addClass('error');
		
		$('#fullpage_overlay').click(function(){ close_message(); $('#message').removeClass('error'); });
		$('#message').click(function(){ close_message(); $('#message').removeClass('error'); }); 
		
		start_message_animate();
	},

	// common function to set timer and start animate
	start_message_animate: function()
	{
		var maxWidth = $('#desktop').width();
		var centerPosition = (maxWidth/2)-(message_width/2);
		$('#message').css('left',centerPosition+'px');
		$('#message').css('top',message_start_position+'px');
		
		$('#fullpage_overlay').show(); 
		
		message_timer = setInterval('message_animate()', message_speed);
		$('#message').fadeIn(message_speed);
	},
	
	show_ajax_loader_in_widget: function(id) {
		this._widgetArray[id].show_ajax_loader();
	},
	show_ajax_error_in_widget: function(id, loadURL) {
		this._widgetArray[id].show_ajax_error(loadURL, Desktop._errorIcon);
	},

	// callback function for timer
	message_animate: function()
	{
		if (message_current_position<0)
		{
			message_current_position += message_tick;
			$('#message').css('top',message_current_position+'px');    
		}
		else
		{
			reset_message();
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
		clearInterval(message_timer);    
		message_timer = null;
		message_current_position = message_start_position;  
	}
	
}

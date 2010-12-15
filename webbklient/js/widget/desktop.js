Desktop = {
	
	_widgetArray : new Array(),
	
	newWidgetWindow : function() {
	
		var id = this._widgetArray.length -1;
		
		var widget = new Widget(id,
		{
			// change theese as needed
			title: browserWidget.widgetTitle,
			width: 800,
			height: 450,
			x: 30,
			y: 15,
		
			// do NOT change theese
			onMinimize:  this.onMinimize, 
			onClose:  this.onClose,
			checkBoundary: true,
			maxWidth: $('#content').width(),
			maxHeight: $('#content').height(),
			bookmarkable: false
		});
		
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
			
			// set callbacks for minimize and close
			eval(wObject+'.onMinimize = function(){ close_widget("'+widgetIconId+'", "'+wObject+'"); }');
			eval(wObject+'.onClose = function(){ reset_widget("'+widgetIconId+'"); }'); 
			
			// run callback to open widget
			eval(wObject+'.open()');
			
			// set state as open and transparency for icon to 20%
			$('#'+widgetIconId).attr('state', 'open');
			$('#'+widgetIconId).css({ 'opacity':'0.2', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"', 'filter':'alpha(opacity=20)' });
		}
		
	},

	// callback for minimize
	close_widget: function(widgetIconId, wObject)
	{
		// close widget
		eval(wObject+'.wnd.close()');
		
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
		var maxWidth = $('#content').width();
		var centerPosition = (maxWidth/2)-(message_width/2);
		$('#message').css('left',centerPosition+'px');
		$('#message').css('top',message_start_position+'px');
		
		$('#fullpage_overlay').show(); 
		
		message_timer = setInterval('message_animate()', message_speed);
		$('#message').fadeIn(message_speed);
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

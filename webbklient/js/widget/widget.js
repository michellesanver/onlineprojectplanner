function Widget(id, wnd_options, partialClasses) {
	
	// Property assignment
	
	// The Id of the instance
	this.id = id;
	
	// The div-id of this instance
	this.divId = "widget_" + id;
	
	this.settingsOpen = false;
	
	// An array containing all partial areas in the window for better updating of smaller parts.
	this.partialClassNames = new Array();
	if (partialClasses != undefined)
	{
		if($.isArray(partialClasses)) {
			this.partialClassNames = partialClasses;
		} else {
			this.partialClassNames.push(partialClasses);
		}
	}
	
	// Event that updates the selected window
	wnd_options.Wid = this.id;
	wnd_options.onSelect = function (){
		Desktop.selectedWindowId = this.Wid;
	};
	
	// Starting JQuery-window object
	if(wnd_options.content == undefined) {
		wnd_options.content = "<div class=\"widget_window\" id=\"" + this.divId + "\"></div>";
	} else {
		wnd_options.content = "<div class=\"widget_window\" id=\"" + this.divId + "\">" + wnd_options.content + "</div>";
	}
	
	this.wnd = $('#desktop').window(wnd_options);
	
	//TODO: SETTINGS IN THE FOOTER
	if(wnd_options.allowSettings) {
		this.wnd.setFooterContent("<a href=\"javascript:void(0);\" onclick=\"Desktop.openSettingsWindow()\"><img src='"+BASE_URL+"images/buttons/small_setting.jpg' alt='Settings' /></a>");
	}
}

// Will set the content in the widget
Widget.prototype.setContent = function(data) {
	$('#' + this.divId).html(data);
}

// Will set the content in a partal area
Widget.prototype.setPartialContent = function(partialClass, data) {
	if($.inArray(partialClass, this.partialClassNames) >= 0) {
		$('#' + this.divId).find('.'+partialClass).html(data);
	}
}

// returns the jquery-windowobject
Widget.prototype.getWindowObject = function() {
	return this.wnd;
}

// Closes the widgetwindow
Widget.prototype.closeWidget = function() {
    this.wnd.close();    
}

// display a ajax spinner
Widget.prototype.show_ajax_loader = function()
{
     // class frame_loading is from jquery.window 
     
     container = $('#' + this.divId);
     
     // show white or black version?  
     /*if ( container.html() == "" )
     {*/
     
         // no content; show white
         var loadingHTML = "<div class='frame_loading'>Loading...</div>"; 
         container.html(loadingHTML);
         var loading = container.children(".frame_loading");
         loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px');
     
     
     // NOTE; the black version if content is set with overlay does not work corrently
     // do NOT delete the code though
     
         
  /*   }
     else
     {
        // has content; show black 
        parentContainer = container;
        while (parentContainer.hasClass("window_panel")==false)
        {
            parentContainer = parentContainer.parents();    
        }   
        
        
        // prepare html 
        var loadingHTML = "<div class='frame_loading-black'>Loading...</div>"; 
        
        // prepare options for overlay
        var overlayOptions = {  
                                'z-index': 2000,
                                'background-color': '#333',
                                'height':parentContainer.css('height'),
                                'width':parentContainer.css('width'),
                                'opacity':'0.5',
                                '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"',
                                'filter':'alpha(opacity=50)',
                                'position': 'absolute',
                                'top': 0,
                                'left': 0
                            }; 
         
		overlay = "<div id=\""+this.divId+"_Overlay\"></div>";
		container.append(overlay);
		$('#'+this.divId+'_Overlay').css(overlayOptions);
        
        // append loading html and set position
        container.append(loadingHTML);
        var loading = container.children(".frame_loading-black");
        loading.css({"marginLeft": '-' + (loading.outerWidth() / 2) -20 + 'px', 'z-index': 2001});  
				
     }      */
     
     
     // NOTE; the black version if content is set with overlay does not work corrently
     // do NOT delete the code though
     
}

// display an error (jquery ui)
Widget.prototype.show_ajax_error = function(loadURL, errorIcon)
{
    // prepare message
    var errorMessage = "<p class=\"ajaxTemplateWidget_Error\">";
    
    // with icon?
    if (errorIcon != undefined || errorIcon != "" || errorIcon != null)
    {
        errorMessage += "<img src=\""+errorIcon+"\" width=\"35\" height=\"35\" />";
    }
    
    // append message
    errorMessage += "Error: Unable to load the page at<br/><br/><small>"+loadURL+"</small></p>";

    // show in div with ID or Class
        $('#'+this.divId).html(errorMessage);
}

// Opens (creates if needed) the settings window
Widget.prototype.setSettingsContent = function(data) {
	if($('#' + this.divId).next('#settings').length == 0) {
		$('#' + this.divId).after('<div id="settings"></div>');
		
	}
	
	$('#' + this.divId).next('#settings').html(data);
	$('#' + this.divId).fadeOut('1000');
	$('#' + this.divId).next('#settings').fadeIn('1000');
	this.settingsOpen = true;
}

// Closes the settingswindow
Widget.prototype.closeSettings = function() {
	$('#' + this.divId).next('#settings').fadeOut('1000');
	$('#' + this.divId).fadeIn('1000');
	this.settingsOpen = false;
}


// returns the settingswindow state.
Widget.prototype.getSettingsState = function() {
	return this.settingsOpen;
}
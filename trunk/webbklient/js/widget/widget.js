function Widget(id, wnd_options) {
	
	this.id = id;
	
	var initialContent = "<div class=\"widget_window\" id=\"widget_" + id + "\"></div>";
	
	wnd_options.content = initialContent;
	
	this.wnd = $('#content').window(wnd_options);
	//this.wnd.setFooterContent("<a href='#'><img src='"+BASE_URL+"images/buttons/small_setting.jpg' alt='Settings' /></a>");

}

Widget.prototype.setContent = function(data) {
	$('#widget_' + this.id).html(data);
}

Widget.prototype.getWindowObject = function() {
	return this.wnd;
};

// display a ajax spinner
Widget.prototype.show_ajax_loader = function(divID, divClass)
{   
     // class frame_loading is from jquery.window 
     var container = null;
     if (divClass != undefined || divClass != "" || divClass != null)
     {
        container = $('.'+divClass);
     }
     else
     {
         container = $('#'+divID);
     }
     
     // show white or black version?  
     if ( container.html() == "" )
     {
         // no content; show white
         var loadingHTML = "<div class='frame_loading'>Loading...</div>"; 
         container.html(loadingHTML);
         var loading = container.children(".frame_loading");
         loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px');
     }
     else
     {
        // has content; show black 
         
        // find top-most div of window
        var parentContainer = container;
        while (parentContainer.hasClass('window_panel')==false)
        {
            parentContainer = parentContainer.parent();    
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
         
        // append overlay to ID or Class     
        var overlay = "";
        if (divClass != undefined || divClass != "" || divClass != null)
        {
            overlay = "<div class=\""+divClass+"_Overlay\"></div>";
            container.append(overlay);
            $('.'+divClass+'_Overlay').css(overlayOptions);
        }
        else
        {
            overlay = "<div id=\""+divID+"_Overlay\"></div>";
            container.append(overlay);
            $('#'+divID+'_Overlay').css(overlayOptions);
        }
        
        // append loading html and set position
        container.append(loadingHTML);
        var loading = container.children(".frame_loading-black");
        loading.css({"marginLeft": '-' + (loading.outerWidth() / 2) -20 + 'px', 'z-index': 2001});  
     }
}

// display an error (jquery ui)
Widget.prototype.show_ajax_error = function(divID, divClass, loadURL, errorIcon)
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
    if (divClass != undefined || divClass != "" || divClass != null)
    {
        $('.'+divClass).html(errorMessage);
    }
    else
    {
        $('#'+divID).html(errorMessage);
    }
}
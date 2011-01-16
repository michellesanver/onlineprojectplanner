  
browserWidget = {   
  
    // widget specific settings
    partialContentDivClass: '', // optional
    widgetTitle: 'Simple browser',
    widgetName: 'browser', // also name of folder
    
    initialContent: "<form class=\"browser_form\"><div class=\"browserTopBar\"><div class=\"browserInnerContent\">Enter a URL: <input type=\"text\" class=\"browserURL\" name=\"browserURL\" id=\"browserURL\" size=\"50\" /> <input type=\"button\" class=\"browserSubmitButton\" value=\"Go!\" onclick=\"browserWidget.getURL();\" /></div></div><div class=\"browserContent\"></div></form>",
    
    // id to current window    
    currentID: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
            
            // set options for window
            var windowOptions = {
                // change theese as needed
                title: browserWidget.widgetTitle,
                width: 800,
                height: 450,
                x: 30,
                y: 15,
                content: browserWidget.initialContent
            };
          
            // create window
            this.currentID = Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, browserWidget.partialContentDivClass, last_position);
        },
        
    // --------------------------------------------------------------------------------------- 
    
    // set content in widgets div, called from the ajax request
    setContent: function(data)  
    {
            Desktop.setWidgetContent(this.currentID, unescape(data));
    },

    // set partial content in widgets div, called from the ajax request
    setPartialContent: function(data)  
    {
            Desktop.setWidgetPartialContent(this.currentID, unescape(data));
    },
        
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL)  
    {
            Desktop.show_ajax_error_in_widget(this.currentID, loadURL);
    },
    
    
    // -----------------------------------------------------------------
    
    getURL: function() {
 
        var url = SITE_URL+"/widget/browser/main/get"; 
        
         // send request
        ajaxRequests.post(this.currentID, 'browser_form', url, 'browserWidget.loadSuccess', 'browserWidget.setAjaxError', false);
    },
    
    // a successfunction from ajaxrequest
    loadSuccess: function(data) {
        
        // get current window and container-div
        var window = Desktop.getWindowObject(this.currentID);
        var container = window.getContainer();
        
        // set content for topbar
        browserWidget.setContent(browserWidget.initialContent);
        
        // fetch size of iframe
        parentContainer = container;
        while (parentContainer.hasClass("window_panel")==false)
        {
            parentContainer = parentContainer.parents();    
        } 
        
        var height = parseInt(parentContainer.css('height'))-87;
        var width = parseInt(parentContainer.css('width'))-7;
        
        // inject iframe through container
        container.find('.browserContent').html("<iframe class=\"browserIFrame\" height=\""+height+"\" width=\""+width+"\" border=\"0\" frameborder=\"0\"></iframe>");
        
        // write to iframe using native javascript
        var iframe = container.find('.browserIFrame');
        doc = iframe[0].contentWindow.document;
        doc.open();
        doc.write(unescape(data));
        doc.close();
    }
    
};
   
   
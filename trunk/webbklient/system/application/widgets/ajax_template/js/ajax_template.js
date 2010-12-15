   
// place widget in a namespace (javascript object simulates a namespace)
ajaxTemplateWidget = {

    // widget specific settings
    partialContentDivClass: 'ajax_template_partial', // optional
    widgetTitle: 'AJAX template',
    widgetName: 'ajax_template', // also name of folder
	
    // id to current window	
	currentID: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(widgetIconId) {
	
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: ajaxTemplateWidget.widgetTitle,
				width: 800,
				height: 450,
				x: 30,
				y: 15
			};
	      
			// create window
			this.currentID = Desktop.newWidgetWindow(windowOptions, widgetIconId, ajaxTemplateWidget.partialContentDivClass);
			
			// load the first page upon start
            var loadFirstPage = SITE_URL+'/widget/' + ajaxTemplateWidget.widgetName + '/some_controller_name';
			ajaxRequests.load(this.currentID, loadFirstPage, "ajaxTemplateWidget.loadSuccess", "ajaxTemplateWidget.setAjaxError");
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
    
    // shows a message (example in start.php)
    example_showMessage: function(message)
        {
            Desktop.show_message(message);    
        },
    
    // wrapper-function that easily can be used inside views from serverside    
    loadURL: function(url, partial) {
        // prepare url
        url = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
        
        // set partial to false if not specified
        if (partial == undefined)
        {
            partial = false;    
        }
        
        // send request
        ajaxRequests.load(ajaxTemplateWidget.currentID, url, 'ajaxTemplateWidget.loadSuccess', 'ajaxTemplateWidget.setAjaxError', partial);
    },
    
    // a successfunction from an ajaxrequest
    loadSuccess: function(data, partial) {
        // partial or full?
        if (partial != undefined && partial == true)
        {
            ajaxTemplateWidget.setPartialContent(data);        
        }
        else
        {
            ajaxTemplateWidget.setContent(data);    
        }
    },
    
    postURL: function(formClass, url, partial) {
        // prepare url
        url = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
        
        // set partial to false if not specified
        if (partial == undefined)
        {
            partial = false;    
        }
        
        // send request
        ajaxRequests.post(ajaxTemplateWidget.currentID, formClass, url, 'ajaxTemplateWidget.loadSuccess', 'ajaxTemplateWidget.setAjaxError', partial);   
    }
    
};

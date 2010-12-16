   
// place widget in a namespace (javascript object simulates a namespace)
ajaxTemplateWidget = {

    // widget specific settings
    partialContentDivClass: 'ajax_template_partial', // optional
    widgetTitle: 'AJAX template',
    widgetName: 'ajax_template', // also name of folder
	
		currentPartial: null,
    
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
			Desktop.newWidgetWindow(windowOptions, widgetIconId, ajaxTemplateWidget.partialContentDivClass);
			
			// load the first page upon start
      var loadFirstPage = SITE_URL+'/widget/' + ajaxTemplateWidget.widgetName + '/some_controller_name/';
			ajaxRequests.load(loadFirstPage, "ajaxTemplateWidget.setContent", "ajaxTemplateWidget.setAjaxError");
		},
		
		
		
		
		/* 
		* The following functions are common for att widgets.
    * --------------------------------------------------------------------------------------- 
    */
		
    // set content in widgets div, called from the ajax request
    setContent: function(data) {
			// The success return function, the data must be unescaped befor use.
			// This is due to ILLEGAL chars in the string.
			Desktop.setWidgetContent(unescape(data));
    },

    // set partial content in widgets div, called from the ajax request
    setPartialContent: function(data) {
			// The success return function, the data must be unescaped befor use.
			// This is due to ILLEGAL chars in the string.
			Desktop.setWidgetPartialContent(this.currentPartial, unescape(data));
			this.currentPartial = null;
    },
    
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL) {
			Desktop.show_ajax_error_in_widget(loadURL);
    },
    
    // shows a message (example in start.php)
    example_showMessage: function(message) {
			Desktop.show_message(message);    
		},
    
    // wrapper-function that easily can be used inside views from serverside    
    loadURL: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
				
        // send request
        ajaxRequests.load(url, 'ajaxTemplateWidget.setContent', 'ajaxTemplateWidget.setAjaxError');
    },
		
		// Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
		loadURLtoPartialTest: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
				
        // set currentpartial to to the classname
        this.currentPartial = ajaxTemplateWidget.partialContentDivClass;
        
        // send request, last parameter = true if this is a partial call. Will skip the loading image.
        ajaxRequests.load(url, 'ajaxTemplateWidget.setPartialContent', 'ajaxTemplateWidget.setAjaxError', true);
    },
		
    // wrapper-function that easily can be used inside views from serverside
    postURL: function(formClass, url) {
        // prepare url
        url = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
				
        // send request
        ajaxRequests.post(formClass, url, 'ajaxTemplateWidget.setContent', 'ajaxTemplateWidget.setAjaxError');   
    }
    
};

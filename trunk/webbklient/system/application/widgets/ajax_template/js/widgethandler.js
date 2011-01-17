// place widget in a namespace (javascript object simulates a namespace)
widgethandler = {

    // widget specific settings
    widgetTitle: 'Widget handler',
    widgetName: 'widget_handler', // also name of folder
	
	currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: widgethandler.widgetTitle,
				width: 800,
				height: 450,
				allowSettings: false
			};
	      
			// create window
			Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, widgethandler.partialContentDivClass, last_position);
			
			// load the first page upon start
            var loadFirstPage = SITE_URL+'/widget/' + widgethandler.widgetName + '/widgets_handler/';
			ajaxRequests.load(loadFirstPage, "widgethandler.setContent", "widgethandler.setAjaxError");
		},
			
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
    
    deleteWidget: function(formClass, url) {
		if(confirm("Are you sure you want to delete this widget?")) {
			widgethandler.postURL(formClass, url);
		}
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
        url = SITE_URL+'/widget/'+widgethandler.widgetName+url;
				
        // send request
        ajaxRequests.load(url, 'widgethandler.setContent', 'widgethandler.setAjaxError');
    },
		
		// Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
	loadURLtoPartialTest: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+widgethandler.widgetName+url;
				
        // set currentpartial to to the classname
        this.currentPartial = widgethandler.partialContentDivClass;
        
        // send request, last parameter = true if this is a partial call. Will skip the loading image.
        ajaxRequests.load(url, 'widgethandler.setPartialContent', 'widgethandler.setAjaxError', true);
    },
		
    
    // wrapper-function that easily can be used inside views from serverside
    postURL: function(formClass, url) {
        // prepare url
        url = SITE_URL+'/widget/'+widgethandler.widgetName+url;
				
		// catching the form data
		var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();
				
        // send request
        ajaxRequests.post(postdata, url, 'widgethandler.setContent', 'widgethandler.setAjaxError');   
    }
    
};

   
// place widget in a namespace (javascript object simulates a namespace)
ajaxTemplateWidget = {

    partialContentDivClass: 'ajaxTemplateContent', // optional
    widgetTitle: 'AJAX template',
    widgetName: 'ajax_template', // also name of folder
	
    // id to current window	
	currentID: null,
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd_handler: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(widgetIconId) {
	
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: this.widgetTitle,
				width: 800,
				height: 450,
				x: 30,
				y: 15
			};
	      
			// create window
			this.currentID = Desktop.newWidgetWindow(windowOptions, widgetIconId, ajaxTemplateWidget.partialContentDivClass);
			
			// load the first page upon start
			ajaxRequests.load(this.currentID, SITE_URL+'/widget/' + ajaxTemplateWidget.widgetName + '/some_controller_name', "ajaxTemplateWidget.setContent", "ajaxTemplateWidget.setAjaxError");
		},
		
    // --------------------------------------------------------------------------------------- 
    
    // set content in widgets div, called from the ajax request
    setContent: function(data)  
        {
            Desktop.setWidgetContent(this.currentID, unescape(data));
        },

    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL)  
        {
					Desktop.show_ajax_error_in_widget(this.currentID, loadURL);
        },
    
    // shows a message (example in start.php)
    example_showMessage: function(message)
        {
            show_message(message);    
        }
    
};

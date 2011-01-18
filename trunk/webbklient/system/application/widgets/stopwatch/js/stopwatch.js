
// place widget in a namespace (javascript object simulates a namespace)
stopwatchWidget = {
	
    pageContentDivClass: 'stopwatch_main_content',
    contentDivClass: 'stopwatch_content',
    widgetTitle: 'Stopwatch',
    widgetName: 'stopwatch', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    currentPartial: null,
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
        			
					var windowOptions = {
		                 title: stopwatchWidget.widgetTitle,
	                     width: 150,
	                     height: 153,
	                     x: 10,
	                     y: 160,
	                     allowSettings: false
		             };
		
		
					Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, stopwatchWidget.partialContentDivClass, last_position);
					
					var loadFirstPage = SITE_URL+'/widget/' + stopwatchWidget.widgetName + '/Pages/';
				                        ajaxRequests.load(loadFirstPage, "stopwatchWidget.setContent", "stopwatchWidget.setAjaxError");
		
                } ,
  
				// set content in widgets div, called from the ajax request
				    setContent: function(data) {
				                        // The success return function, the data must be unescaped befor use.
				                        // This is due to ILLEGAL chars in the string.	
				                        Desktop.setWidgetContent(unescape(data));
										$('#clock1').stopwatch();
										
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
				        url = SITE_URL+'/widget/'+stopwatchWidget.widgetName+url;

				        // send request
				        ajaxRequests.load(url, 'stopwatchWidget.setContent', 'stopwatchWidget.setAjaxError');
				    },

				                // Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
				                loadURLtoPartialTest: function(url) {
				        // prepare url
				        url = SITE_URL+'/widget/'+stopwatchWidget.widgetName+url;

				        // set currentpartial to to the classname
				        this.currentPartial = stopwatchWidget.partialContentDivClass;

				        // send request, last parameter = true if this is a partial call. Will skip the loading image.
				        ajaxRequests.load(url, 'stopwatchWidget.setPartialContent', 'stopwatchWidget.setAjaxError', true);
				    },

				    // wrapper-function that easily can be used inside views from serverside
				    postURL: function(formClass, url) {
				        // prepare url
				        url = SITE_URL+'/widget/'+stopwatchWidget.widgetName+url;

				                                // catching the form data
				                                var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();

				        // send request
				        ajaxRequests.post(postdata, url, 'stopwatchWidget.setContent', 'stopwatchWidget.setAjaxError');   
				    }

				};

// place widget in a namespace (javascript object simulates a namespace)
apechatWidget = {
	
    pageContentDivClass: 'apechat_main_content',
    contentDivClass: 'apechat_content',
    widgetTitle: 'apechat',
    widgetName: 'apechat', // also name of folder
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
		                 title: apechatWidget.widgetTitle,
	                     width: 600,
	                     height: 450,
	                     x: 10,
	                     y: 15,
		             };
		
					Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, apechatWidget.partialContentDivClass, last_position);
					
					var loadFirstPage = SITE_URL+'/widget/' + apechatWidget.widgetName + '/Pages/';
				                        ajaxRequests.load(loadFirstPage, "apechatWidget.setContent", "apechatWidget.setAjaxError");
		
                } ,
  
				// set content in widgets div, called from the ajax request
				    setContent: function(data) {
				                        // The success return function, the data must be unescaped befor use.
				                        // This is due to ILLEGAL chars in the string.	
				                        
										
                                        // find which .digiapechat to use through selected window's children
                                        //$('#widget_' + Desktop.selectedWindowId ).find('.digiapechat').jdigiapechat({ }); 
                                       

										
 										$.ajax({
										  url: "http://www.pppp.nu/ape-jsf/Demos/Chat/demo.html",
										  cache: false,
										  success: function(html){
										    $("#apechat").append(html);
										  }
										});
										
										
										
                                        /*
                                        OLD CODE; does not work with multiple instances
                                        $('.digiapechat').jdigiapechat({ });
                                        */
										//Desktop.setWidgetContent(unescape(data));
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
				        url = SITE_URL+'/widget/'+apechatWidget.widgetName+url;

				        // send request
				        ajaxRequests.load(url, 'apechatWidget.setContent', 'apechatWidget.setAjaxError');
				    },

				                // Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
				                loadURLtoPartialTest: function(url) {
				        // prepare url
				        url = SITE_URL+'/widget/'+apechatWidget.widgetName+url;

				        // set currentpartial to to the classname
				        this.currentPartial = apechatWidget.partialContentDivClass;

				        // send request, last parameter = true if this is a partial call. Will skip the loading image.
				        ajaxRequests.load(url, 'apechatWidget.setPartialContent', 'apechatWidget.setAjaxError', true);
				    },

				    // wrapper-function that easily can be used inside views from serverside
				    postURL: function(formClass, url) {
				        // prepare url
				        url = SITE_URL+'/widget/'+apechatWidget.widgetName+url;

				                                // catching the form data
				                                var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();

				        // send request
				        ajaxRequests.post(postdata, url, 'apechatWidget.setContent', 'apechatWidget.setAjaxError');   
				    }

				};
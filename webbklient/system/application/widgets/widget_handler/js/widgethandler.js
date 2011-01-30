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
    
    // confirm that user really wants to delete
    deleteWidget: function(formClass, url, dialog_id) {
        
        // show dialog to confirm or cancel       
        $( "#"+dialog_id ).dialog({
            resizable: false,
            height: 185 ,
            width: 400,
            modal: true,
            zIndex: 3999,
            buttons: {
                    
                    // action to continue and delete
                    "Continue": function() {
                        $( this ).dialog( "close" );
                        widgethandler.postURL(formClass, url);
                    },
                    
                    // action to cancel
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                    
            }
        });     
                
    },
    
    // set new name for a widget
    rename_last_validation_error_id: "",
    rename_has_new_height: false,
    renameWidget: function(formClass, url, dialog_id, input_id, id_invalid_chars, id_invalid_length, id_invalid_empty, current_name) {
        
        // new height of dialog on validation error
        var dialog_error_height = 270;
            
        // copy name to input
        document.getElementById(input_id).value = current_name;
            
            // show dialog
        $( "#"+dialog_id ).dialog({
            resizable: false,
            height: 215,
            width: 350,
            modal: true,
            zIndex: 3999,
            buttons: {
                    
                    // action to save new name
                    "Save": function() {
                        
                        // clear old validation error if set
                        if (widgethandler.rename_last_validation_error_id != "") {
                            $('#'+widgethandler.rename_last_validation_error_id).hide();
                            widgethandler.rename_last_validation_error_id = "";
                        }
                        
                        // get new value
                        var postValue = document.getElementById(input_id).value;
                        
                        // validate as not empty
                        if ( postValue == "" ) {
                            
                            // show error
                            widgethandler.rename_last_validation_error_id = id_invalid_empty;
                            $('#'+id_invalid_empty).show();
                            
                            // set new height
                            if (widgethandler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widgethandler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                        
                        // validate as max 30 chars
                        } else if (postValue.length > 30) {
                            
                            // show error
                            widgethandler.rename_last_validation_error_id = id_invalid_chars;
                            $('#'+id_invalid_chars).show();
                            
                            // set new height
                            if (widgethandler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widgethandler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                        
                        }
                        
                        // all ok; close and save to database
                        $( this ).dialog( "close" );
                        
                        
                        alert('not implemented');
                        
                        
                    },
                    
                    // action to cancel
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
 
            }
        }); 
 
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

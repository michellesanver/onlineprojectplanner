/* 
* Name: Widget handler
* Desc: A widget to handle all the other widgets
* Last update:10/2 - 2011 Michelle Sanver
*/
function widget_handler(id, wnd_options) {
	this.widgetName = "widget_handler";
	this.title = "Widget handler";
	var partialClasses = [''];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = false;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	this.create(id, wnd_options, partialClasses);
}

widget_handler.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
widget_handler.prototype.index = function() {
	// load the first page upon start
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/widgets_handler/index/';
	ajaxRequests.load(this.id, url, "eventinit");
};

widget_handler.prototype.eventinit = function(data) {
	var that = this;
	
	//Load the view
	$('#' + this.divId).html(data);
	
	$("#your_widgets").sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function () {  
           var order = $('#your_widgets').sortable('serialize');
           $.post(SITE_URL+'/widget/_core/' + that.widgetName + '/widgets_handler/sort/', order);
        }
    });
    
    //Add widget
    $('#' + this.divId).find("#addwidget").click(function() {
		var widgetid = $(this).attr("class");
		var url = SITE_URL+'/widget/_core/' + that.widgetName + '/widgets_handler/' + widgetid;
		ajaxRequests.load(that.id, url, "eventinit");
		return false;
	});
    
	//Deletebutton on click
	$('#' + this.divId).find(".widgets_handler_delete_button").click(function() {
		var removeidnode = $(this).parents('li[id^="widgetslist_"]').attr("id");
		var removeid = removeidnode.split('_')[1];
		Desktop.callWidgetFunction(this, "deleteWidget", removeid);
		return false;
	});
	
	//Renamebutton on click
	$('#' + this.divId).find(".widgets_handler_rename_button").click(function() {
		var renameidnode = $(this).parents('li[id^="widgetslist_"]').attr("id");
		var renameid = renameidnode.split('_')[1];
		var form = $(this).parents('form[class^="actionform"]').attr("class");
		var name = this.id;
		Desktop.callWidgetFunction(this, "renameWidget", form, name, renameid);
		
		return false;
	});
	
};


widget_handler.prototype.rename_last_validation_error_id = "";
widget_handler.prototype.rename_has_new_height = false;
    
widget_handler.prototype.renameWidget = function(args) {
	var that = this;
	var rename_last_validation_error_id = "";
    var rename_has_new_height = false;
	var formClass = args[0];
	var url = SITE_URL+'/widget/_core/' + that.widgetName + '/widgets_handler/rename/';
	var dialog_message_id = "widgets-handler-processing-message";
	var dialog_processing_id = "widgets-handler-dialog-processing";
	var dialog_id = "widgets-handler-dialog-new-name";
	var input_id = "widget_new_name";
	var id_invalid_chars = "widget_new_name_error";
	var id_invalid_length = "widget_new_name_error2";
	var id_invalid_empty = "widget_new_name_error3";
	var current_name = args[1];
	var widgetid = args[2];

	//Copy name to input
	document.getElementById(input_id).value = current_name;
	// new height of dialog on validation error
    var dialog_error_height = 270;
        
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
                        if (widget_handler.rename_last_validation_error_id != "") {
                            $('#'+widget_handler.rename_last_validation_error_id).hide();
                            widget_handler.rename_last_validation_error_id = "";
                        }
                        
                        // get value from form
                        var widgetName = document.getElementById("widget_new_name").value;
						                                                
                        // create regexp for validation
                        var charPattern = /[^a-z0-9()\sедц]/i  // all except allowed chars
                        
                        // validate as not empty
                        if ( widgetName == "" ) {
                            
                            // show error
                            widget_handler.rename_last_validation_error_id = id_invalid_empty;
                            $('#'+id_invalid_empty).show();
                            
                            // set new height
                            if (widget_handler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widget_handler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                        
                        // validate as max 30 chars
                        } else if (widgetName.length > 30) {
                            
                            // show error
                            widget_handler.rename_last_validation_error_id = id_invalid_length;
                            $('#'+id_invalid_length).show();
                            
                            // set new height
                            if (widget_handler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widget_handler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                        
                        // check for invalid characters
                        } else if ( widgetName.match(charPattern) ) {
                         
                            // show error
                            widget_handler.rename_last_validation_error_id = id_invalid_chars;
                            $('#'+id_invalid_chars).show();
                            
                            // set new height
                            if (widget_handler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widget_handler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                            
                        }
                       
                        // all ok; close and save to database
                        $( this ).dialog( "destroy" ).remove();
    					
                        // prepare data to send
                        var postdata = { 'instanceId':that.id, 'widgetId': widgetid, 'widgetName': widgetName, 'dialogProcessingId': dialog_processing_id, 'dialogMessageId': dialog_message_id };
                                
                        // send request
                        ajaxRequests.post(that.id, postdata, url, 'eventinit');
                        
                         
                        
            },
                
            // action to cancel
            Cancel: function() {
                $( this ).dialog( "destroy" ).remove();
            }

        }
	});
	
};    

widget_handler.prototype.deleteWidget = function(remove_id) {
	var that = this;
	var dialog_id = "widgets-handler-dialog-confirm";
	var removeId = remove_id;
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/widgets_handler/index/'; 
	
	// Gathering the data from the form with the selialize function.
	var postData = {'widgetid': removeId}
	// The postcommand. No big change from the load command exept the postData-parameter.
	
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
	            ajaxRequests.post(that.id, postData, url, "eventinit");
	        },
	        
	        // action to cancel
	        Cancel: function() {
	            $( this ).dialog( "close" );
	        }
	            
	    }
	});   
};




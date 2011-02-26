/* 
* Name: Widget handler
* Desc: A widget to handle all the other widgets
* Last update: 16/2-2011 Dennis Sangmo
*/
function widgethandler(id, divid, widgeturl) {
	this.widgetName = "widget_handler";
	this.title = "Widget handler";
	this.id = id;
	this.divId = divid;
	this.widgetUrl = widgeturl;
}

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
widgethandler.prototype.index = function() {
	// load the first page upon start
	ajaxRequests.load(this.id, this.widgetUrl + 'widgets_handler/index/', "WH_eventinit", true);
};

widgethandler.prototype.eventinit = function() {
	
	// close dialog processing (if any active)
    $.jprocessing( "close" );
	
	// setup widget
	var that = this;
	
	// move widget
	$('#' + this.divId).find("#your_widgets").sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function () {  
           var order = $('#'+that.divId).find('#your_widgets').sortable('serialize');
		   that.moveWidget(order);
        }
    });
    
    //Add widget
    $('#' + this.divId).find("#addwidget").click(function() {
		var widgetid = $(this).attr("class")
		that.addWidget(widgetid);
		return false;
	});
    
	//Deletebutton on click
	$('#' + this.divId).find(".widgets_handler_delete_button").click(function() {
		var removeidnode = $(this).parents('li[id^="widgetslist_"]').attr("id");
		var removeid = removeidnode.split('_')[1];
		that.deleteWidget(removeid);
		return false;
	});
	
	//Renamebutton on click
	$('#' + this.divId).find(".widgets_handler_rename_button").click(function() {
		var renameidnode = $(this).parents('li[id^="widgetslist_"]').attr("id");
		var renameid = renameidnode.split('_')[1];
		var form = $(this).parents('form[class^="actionform"]').attr("class");
		var nameid = this.id;
		var name = nameid.split('_')[1];
		that.renameWidget(form, name, renameid);
		
		return false;
	});
	
	// any new widgets added?
	var new_widget = $('#new_widget_added');
	if ( new_widget[0] != undefined ) {
		var new_json_data = widgethandler_new_widget_json; // global variable from view
		var order = $('#'+that.divId).find('#your_widgets').sortable('toArray'); // get order of widgets
		
		// cut "widgetslist_" from all elements in array
		for (var n=0; n<order.length; n++) {
			order[n] = order[n].replace('widgetslist_','');
			order[n] = parseInt(order[n]);
		}
		
		// does widget exist? (if multiple instances; don't load again)
		try {
			
			var obj = eval(new_json_data.widget_object_name);
			
		} catch(err) {
			
			// more scripts/css are required; force user to reload page
			var dialog_id = 'dialog-refresh-page';
			var dialogHTML = dialogHTML = '<div id="' + dialog_id + '" title="Message" style="display:none;">'+
							   '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The new widget needs more resources to be loaded. Press Ok (or Cancel) to refresh current page.</p>'+
							   '</div>';

			// inject a div into body to use for dialog
			$(document.body).append(dialogHTML);
			
			// create dialog
			 $("#" + dialog_id).dialog({
				resizable: false,
				height: 200,
				width: 500,
				modal: true,
				zIndex: 3999,
				buttons: {
					'Ok': function() {
						// destroy and remove dialog
						$(this).dialog("destroy");
						$('#'+dialog_id).remove();
						
						// force refresh
						document.location = document.location;
					},
					Cancel: function() {
						// destroy and remove dialog
						$(this).dialog("destroy");
						$('#'+dialog_id).remove();
						
						// force refresh
						document.location = document.location;
					}
				}
			});
			
		}
		
		// uppdate widgetbar
		WidgetBar.addWidget(new_json_data, order);
	}
	
};


widgethandler.prototype.rename_last_validation_error_id = "";
widgethandler.prototype.rename_has_new_height = false;
    
widgethandler.prototype.renameWidget = function(form, name, renameid) {
	var that = this;
	var rename_last_validation_error_id = "";
    var rename_has_new_height = false;
	var formClass = form;
	var url = that.widgetUrl + 'widgets_handler/rename/';
	var dialog_message_id = "widgets-handler-processing-message";
	var dialog_processing_id = "widgets-handler-dialog-processing";
	var dialog_id = "widgets-handler-dialog-new-name";
	var input_id = "widget_new_name";
	var id_invalid_chars = "widget_new_name_error";
	var id_invalid_length = "widget_new_name_error2";
	var id_invalid_empty = "widget_new_name_error3";
	var current_name = name;
	var widgetid = renameid;

	//Copy name to input
	document.getElementById(input_id).value = current_name;
	// new height of dialog on validation error
    var dialog_error_height = 270;
        
    // show dialog
    $( "#"+dialog_id ).dialog({
        resizable: false,
        height: 215,
        width: 375,
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
                        
                        // get value from form
                        var widgetName = document.getElementById("widget_new_name").value;
						                                                
                        // create regexp for validation
                        var charPattern = /[^a-z0-9()\sедц]/i  // all except allowed chars
                        
                        // validate as not empty
                        if ( widgetName == "" ) {
                            
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
                        } else if (widgetName.length > 30) {
                            
                            // show error
                            widgethandler.rename_last_validation_error_id = id_invalid_length;
                            $('#'+id_invalid_length).show();
                            
                            // set new height
                            if (widgethandler.rename_has_new_height==false) {
                                $( "#"+dialog_id ).dialog( "option", "height", dialog_error_height );
                                widgethandler.rename_has_new_height = true;
                            }
                            
                            // exit function
                            return;
                        
                        // check for invalid characters
                        } else if ( widgetName.match(charPattern) ) {
                         
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
                        $( this ).dialog( "destroy" ).remove();
    					
						// show dialog processing
						$.jprocessing( { 'message':'Setting new name for widget...' } );
						
                        // prepare data to send
                        var postdata = { 'instanceId':that.id, 'widgetId': widgetid, 'widgetName': widgetName, 'dialogProcessingId': dialog_processing_id, 'dialogMessageId': dialog_message_id };
                                
                        // send request
                        ajaxRequests.post(that.id, postdata, url, 'WH_eventinit', true);
                        
                         
                        // change name in widgetbar
			WidgetBar.updateWidgetName(widgetid, widgetName);
			
			// change name in internal data
			Desktop.saveWidgetData(widgetid, { 'last_name': widgetName });
            },
                
            // action to cancel
            Cancel: function() {
                $( this ).dialog( "destroy" ).remove();
            }

        }
	});
	
};    

widgethandler.prototype.deleteWidget = function(remove_id) {
	
	var dialog_id = 'widgets-handler-dialog-confirm';
	
	// save data for ajax-call
	var that = this;
	var removeId = remove_id;
	var postData = { 'widgetid': removeId };
	var url = this.widgetUrl + 'widgets_handler/index/';
	
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
				
				// delete dialog
	            $( this ).dialog( "destroy" );
				
				// show dialog processing
				$.jprocessing( { 'message':'Removing selected widget...' } );
				
				// send ajax-call
	            ajaxRequests.post(that.id, postData, url, "WH_eventinit", true);
				
				// delete widget in widgetbar
				WidgetBar.deleteWidgetIcon(removeId);
	        },
	        
	        // action to cancel
	        Cancel: function() {
	            $( this ).dialog( "destroy" );
	        }
	            
	    }
	});  
};

widgethandler.prototype.addWidget = function(widgetid) {
	
		// show dialog processing
		$.jprocessing( { 'message':'Adding widget to project...' } );
		
		// send ajax-call
		ajaxRequests.load(this.id, this.widgetUrl + 'widgets_handler/' + widgetid, "WH_eventinit", true);	
	
};


widgethandler.prototype.moveWidget = function(order) {
	
		// show dialog processing
		$.jprocessing( { 'message':'Setting new position of widget...' } );
	
		// send ajax-call and update position
		$.post(this.widgetUrl + 'widgets_handler/sort/', order, function(data){
		
			// update positions in widgetbar with data from reponse
			var new_positions = $.parseJSON(data);
			
			// run function in widgetbar to do the update
			WidgetBar.sortWidgets(new_positions);
		
		});
		
};

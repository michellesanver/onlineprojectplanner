/*
 * jQuery UI Dialog wrappers
 *
 * Author: Fredrik Johannson <tazzie76@gmail.com>
 * Project: Online Project Planner
 * URL: https://code.google.com/p/onlineprojectplanner/
 *
 *
 * Description:
 *   a JQuery wrapper plugin for UI Dialog. When executed
 *   the plugin-function injects a div into the DOM and uses it to
 *   display an UI Dialog.
 *  
 */

(function( $ ){
    
    /**
    *   Wrapper for a Confirm-dialog
    * 
    *   - When Ok or Cancel is pressed
    *     the dialog is destroyed and the div-element is removed.
    *     If the Ok-button is pressed a callback also occurs to
    *     the specified function in options.
    * 
    *
    * Example 1 - using a global callback:
    *
    *     function show_confirm_delete() {
    *            // set options
    *             var options = {
    *                'question': 'Are you absolutely sure you want to delete this page? Action is permanent.',
    *                'title': 'Confirm delete',
    *                'callback_function': 'delete_ok_callback',
    *                'callback_is_global': true
    *            };
    *       
    *            // create dialog
    *            $.jconfirm(options);
    *        }
    *   
    *        function delete_ok_callback() {
    *       
    *            // process delete after ok was pressed
    *       
    *        }
    *
    *
    * Example 2 - using a callback in widget:
    * 
    *      function show_confirm_delete() {
    *            // set options
    *             var options = {
    *                'question': 'Are you absolutely sure you want to delete this page? Action is permanent.',
    *                'title': 'Confirm delete',
    *                'callback_function': 'delete_ok_callback',
    *                'widget_project_id': 174
    *            };
    *       
    *            // create dialog
    *            $.jconfirm(options);
    *        }
    *
    *      wikiWidget.prototype.delete_ok_callback = function() {
    *
    *             // process delete after ok was pressed
    *   
    *       }
    * 
    *
    * Options:
    *    title - title of dialog (default empty)
    *    question - question in main dialog
    *    
    *    width  - width of dialog (default: 500)
    *    height - height of dialog (default: 200)
    *    z_index - z-index of dialog (default: 3999)
    *    resizable - boolean value, set to true if dialog should be resizable (default: false)
    *
    *    callback_is_global - boolean value, set to true if callback is in the global namespace
    *    callback_function - a string with the name of the callback (global or in a widget)
    *    widget_project_id - id to the current widget (widget_projects_id)
    *
    *    dialog_id - a custom string value with id to the dialog that will be inserted (default: dialog-confirm)
    *
    *    dialog_content - a custom string with the html-code that will be inserted for the dialog. default value is:
    *    
    *                     <div id="' + dialog_id + '" title="' + title + '" style="display:none;">
    *                     <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + message + '</p>
    *                     </div>
    *
    *
    *  Minimum required options:
    *    question
    *    callback_function ( with widget_project_id OR callback_is_global )
    */    
    $.jconfirm = function(options) {
        
        // get values from options or set default
        var d_title = (options.title != undefined ? options.title : '');
        var d_message = (options.question != undefined ? options.question : 'What do you want to ask?');
        var d_width = (options.width != undefined ? options.width : 500);
        var d_height = (options.height != undefined ? options.height : 200);
        var d_z_index = (options.z_index != undefined ? options.z_index : 3999);
        var d_resizable = (options.resizable != undefined ? options.resizable : false);
        var dialog_id = (options.dialog_id != undefined ? options.dialog_id : 'dialog-confirm');
        
        // which dialog html-code? default or custom
        var dialogHTML = "";
        if ( options.dialog_content == undefined ) {
        
            dialogHTML = '<div id="' + dialog_id + '" title="' + d_title + '" style="display:none;">'+
                           '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + d_message + '</p>'+
                           '</div>';
        } else {
            dialogHTML = options.dialog_content;   
        }
        
        // inject a div into body to use for dialog
        $(document.body).append(dialogHTML);
        
        // create dialog
         $("#" + dialog_id).dialog({
            resizable: d_resizable,
            height: d_height,
            width: d_width,
            modal: true,
            zIndex: d_z_index,
            buttons: {
                'Ok': function() {
                    // destroy and remove dialog
                    $(this).dialog("destroy");
                    $('#'+dialog_id).remove();
                    
                    // run callback
                    if (options.callback_is_global != undefined && options.callback_is_global == true && options.callback_function != undefined) {
                        // call global function
                        eval(options.callback_function + '();');
                        
                    } else if (options.widget_project_id != undefined && options.callback_function != undefined) {
                        // call widget function
                        Desktop.callWidgetFunction(options.widget_project_id, options.callback_function);
                        
                    } else {
                        // error in options
                        alert("Error; No callback! I don't know what to do... :'(");
                    }
                },
                Cancel: function() {
                    // destroy and remove dialog
                    $(this).dialog("destroy");
                    $('#'+dialog_id).remove();
                }
            }
        });
        
    };
    
    /**
     * a simple wrapper to display a processing dialog
     *
     * Usage:
     *       $.jprocessing( options );
     *
     *       - where options is either a string with "close" or an object with
     *         parameters which will open a new processing dialog
     *
     * Example 1:
     *
     *     function do_something() {
     *
     *        // open a processing dialog
     *        $.jprocessing( { 'title':'Processing', 'message': 'Please wait while removing image...'} ;)
     *
     *        // do something like an ajax-call 
     *     }
     *
     *     function callback_something() {
     *   
     *        // this function is called from previous upon completion
     *
     *        // close processing-dialog
     *        $.jprocessing( "close" );
     *     }
     *
     *
     * Options:
     *    title - title of dialog (default: 'Please wait')
     *    message - message in main dialog (default: 'Please wait while processing request')  
     *    width  - width of dialog (default: 350)
     *    height - height of dialog (default: 175)
     *    z_index - z-index of dialog (default: 3999)
     *    dialog_id - a custom string value with id to the dialog that will be inserted (default: dialog-processing)
     */    
    $.jprocessing = function(options) {
    
        // open new or close existing?
        if ( typeof options == "string" && options == "close")
        {
            
            //
            // close dialog
            //
            
            if ( this.dialog_id != undefined && this.dialog_id != "")
            {
                $('#'+this.dialog_id).dialog('destroy');
                $('#'+this.dialog_id).remove();
                
                this.dialog_id = "";
            }
            
        } else if (this.dialog_id != undefined && this.dialog_id != "") {
            
            alert('Hey! Close the previous processing dialog ;)');
            
        } else {
      
            //
            // create new
            //
        
            var title = (options.title != undefined ? options.title : 'Please wait');
            var message = (options.message != undefined ? options.message : 'Please wait while processing request');
            var width = (options.width != undefined ? options.width : 350);
            var height = (options.height != undefined ? options.height : 175);
            var z_index = (options.z_index != undefined ? options.z_index : 3999);
            var dialog_id = (options.dialog_id != undefined ? options.dialog_id : 'dialog-processing');
            this.dialog_id = dialog_id; // save for when closed
            
            var dialogHTML = '<div id="' + dialog_id + '" title="' + title + '" style="display:none;">'+
                             '<p>' + message + '</p>'+
                             '</div>';
            
            $(document.body).append(dialogHTML);
            
            $('#'+dialog_id).dialog({
              resizable: false,     
              height: height,
              width: width,
              modal: true,
              zIndex: z_index, 
              buttons: { }
            });
            
        }
    };
    
    /**
     * a simple wrapper to display a result dialog (message)
     *
     * Usage:
     *       $.jresult( options );
     *
     *       - where options is an object with parameters
     *
     * Example 1:
     *
     *     function do_something() {
     *
     *        // open a processing dialog
     *        $.jresult( { 'message': 'A white rabbit ran through the room'} ;)
     *
     *     }
     *
     *
     * Options:
     *    title - title of dialog (default: 'Result')
     *    message - message in main dialog (default: 'Some result happend by a random action')  
     *    width  - width of dialog (default: 350)
     *    height - height of dialog (default: 175)
     *    z_index - z-index of dialog (default: 3999)
     *    dialog_id - a custom string value with id to the dialog that will be inserted (default: dialog-result)
     */ 
    $.jresult = function(options) {
        
        var title = (options.title != undefined ? options.title : 'Result');
        var message = (options.message != undefined ? options.message : 'Some result happend by a random action');
        var width = (options.width != undefined ? options.width : 350);
        var height = (options.height != undefined ? options.height : 175);
        var z_index = (options.z_index != undefined ? options.z_index : 3999);
        var dialog_id = (options.dialog_id != undefined ? options.dialog_id : 'dialog-result');
            
        var dialogHTML = '<div id="' + dialog_id + '" title="' + title + '" style="display:none;">'+
                         '<p>' + message + '</p></div>';
        
        $(document.body).append(dialogHTML);
        
        // show result
        $('#'+dialog_id).dialog({
            resizable: false,     
            height: height,
            width: width,
            modal: true,
            zIndex: z_index, 
            buttons: {
                "Ok": function() {
                    // destroy and remove html
                    $( this ).dialog( "destroy" );
                    $('#'+dialog_id).remove();
                }
            }
        });  
        
    };
    
})( jQuery );

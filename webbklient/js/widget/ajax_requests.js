ajaxRequests = {

    // function that will load an url and set resulting data into specified div
	// Used by the widgets
    load: function(pwID, loadURL, successFunction, partial)
	{
		// empty url?
		if (loadURL == "") {
			Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
			return;
		}
		
		// show ajax spinner
		if (partial == undefined || partial == false) {
			Desktop.show_ajax_loader_in_widget(pwID);
		}

		$.ajax({
		  type: 'GET',
		  url: loadURL,
		  success: function(data){
				if (typeof partial == "string") {
					Desktop.callWidgetFunction(pwID, successFunction, data, partial);
				} else {
					Desktop.callWidgetFunction(pwID, successFunction, data);
				}
		  },
		  error: function(xhr, statusSTR, errorSTR) {
				Desktop.show_ajax_error_in_widget(loadURL, pwID);
		  }
	   });      
	},
	
	
    // post data
	// User by the widgets
    post: function(pwID, postdata, loadURL, successFunction, partial)
    {
        // empty url?
        if (loadURL == "")
        {
            Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
            return;
        }
        // empty postdata?
        else if (postdata == "")
        {
            Desktop.show_errormessage('Hey! :\'( No data found to submit? *confused*');
            return;
        }
		
		// show ajax spinner
		if (partial == undefined || partial == false) {
			Desktop.show_ajax_loader_in_widget(pwID);
		}
		
        // post with ajax
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
          success: function(data){
                // set new content
				if (typeof partial == "string") {
					Desktop.callWidgetFunction(pwID, successFunction, data, partial);
				} else {
					Desktop.callWidgetFunction(pwID, successFunction, data);
				}
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
				Desktop.show_ajax_error_in_widget(loadURL, pwID);
          }
       });
       
       return false;   
    },
    
    // function that will load an url and set resulting data into specified div
    load_full: function(loadURL, successFunction, errorFunction, args)
	{
		// empty url?
		if (loadURL == "") {
			Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
			return;
		}
		
		$.ajax({
		  type: 'GET',
		  url: loadURL,
		  success: function(data){
				var success = successFunction.split(".");
				window[success[0]][success[1]](data, args);
		  },
		  error: function(xhr, statusSTR, errorSTR) {
				var error = errorFunction.split(".");
				window[error[0]][error[1]](loadURL, args);
		  }
	   });      
	},
    // post data
	// used by other objects
    post_full: function(postdata, loadURL, successFunction, errorFunction, args)
    {
        // empty url?
        if (loadURL == "")
        {
            Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
            return;
        }
        // empty postdata?
        else if (postdata == "")
        {
            Desktop.show_errormessage('Hey! :\'( No data found to submit? *confused*');
            return;
        }
        
        // post with ajax
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
		  success: function(data){
				if(successFunction != "" || successFunction != undefined){
					var success = successFunction.split(".");
					window[success[0]][success[1]](data, args);
				}
		  },
		  error: function(xhr, statusSTR, errorSTR) {
				if(errorFunction != "" || errorFunction != undefined){
					var error = errorFunction.split(".");
					window[error[0]][error[1]](loadURL, args);
				}
          }
       });
       
       return false;   
    }

}
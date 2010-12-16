ajaxRequests = {

    // function that will load an url and set resulting data into specified div
    load: function(loadURL, successFunction, errorFunction, partial)
        {
            // empty url?
            if (loadURL == "")
            {
                Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            // show ajax spinner
						if(!partial){
							Desktop.show_ajax_loader_in_widget();
						}

            $.ajax({
              type: 'GET',
              url: loadURL,
              success: function(data){
                    // set new content
					data = escape(data);
										
                    eval(successFunction + '("' + data + '")');
              },
              error: function(xhr, statusSTR, errorSTR) {
                    // display an error
                    eval(errorFunction + "('" + loadURL + "');");
              }
           });      
        },
    
    // post data
    post: function(formClass, loadURL, successFunction, errorFunction, partial)
    {
        var postdata = $('#widget_' + windowID ).find('.' + formClass).serialize();
      
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
				if(!partial){
					Desktop.show_ajax_loader_in_widget();
				}
				
        // post with ajax
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
          success: function(data){
                // set new content
					data = escape(data);
										
                    eval(successFunction + '("' + data + '", '+partial+')');
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
                    eval(errorFunction + "('" + loadURL + "');");
          }
       });
       
       return false;   
    }

}
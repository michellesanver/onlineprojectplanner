
// place widget in a namespace (javascript object simulates a namespace)
clockWidget = {
	
    pageContentDivClass: 'clock_main_content',
    contentDivClass: 'clock_content',
    widgetTitle: 'Clock',
    widgetName: 'clock', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function() {
        
                    // create the first view
                    var initialContent = "<div class=\""+clockWidget.contentDivClass+"\"></div>";
        
                    // create a new jquery window
                    this.wnd = $('#content').window({
                        // change theese as needed
                       title: clockWidget.widgetTitle,
                       content: initialContent,
                       width: 220,
                       height: 200,
                       x: 10,
                       y: 15,
                       
                       // do NOT change theese
                       onMinimize:  this.onMinimize, 
                       onClose:  this.onClose,
                       checkBoundary: true,
                       maxWidth: $('#content').width(),
                       maxHeight: $('#content').height(),
                       bookmarkable: false,
					   showFooter:false,
					   resizable:false,
					   maximizable: false,
					   minimizable: false
                    });
        				
                    // load the first page upon start
                    clockWidget.load('/pages');
					
        
                } ,
                
    // function that will load an url and set resulting data into specified div
    // if parameter into_page_content is set then the content is put into div.clock_main_content 
    load: function(url, into_page_content)
        {
            
            // empty url?
            if (url == "")
            {
                show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            var whichDiv = clockWidget.contentDivClass;
            if (into_page_content != undefined && into_page_content == true)
            {
                whichDiv = clockWidget.pageContentDivClass;
            }
            
            // show ajax spinner
            show_ajax_loader(null, whichDiv);
            
            // load with ajax
            var loadURL = SITE_URL+'/widget/'+clockWidget.widgetName+url;
            $.ajax({
              type: 'GET',
              url: loadURL,
              success: function(data){
                    // set new content
                    $('.'+whichDiv).html(data);
					$('#digiclock').jdigiclock({ });
					
              },
              error: function(xhr, statusSTR, errorSTR) {
                    // display an error
                    show_ajax_error(null, whichDiv, loadURL, clockWidget.errorIcon);
              }
           });      
           
           return false; 
        },
    
    // post data
    post: function(formID, url)
    {
        var postdata = $('#'+formID ).serialize();
      
        // empty url?
        if (url == "")
        {
            show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
            return;
        }
        // empty postdata?
        else if (postdata == "")
        {
            show_errormessage('Hey! :\'( No data found to submit? *confused*');
            return;
        }
        
        // show ajax spinner
        show_ajax_loader(null, clockWidget.contentDivClass);
                 
        // post with ajax
        var loadURL = SITE_URL+'/widget/'+clockWidget.widgetName+url;
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
          success: function(data){
                // set new content
                clockWidget.setContent(data);
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
                show_ajax_error(null, clockWidget.contentDivClass, loadURL, clockWidget.errorIcon);
          }
       });
       
       return false;   
    }
} 

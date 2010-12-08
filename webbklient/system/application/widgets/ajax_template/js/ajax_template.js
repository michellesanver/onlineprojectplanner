   
// place widget in a namespace (javascript object simulates a namespace)
ajaxTemplateWidget = {

    contentDivClass: 'ajaxTemplateContent',
    widgetTitle: 'AJAX template',
    widgetName: 'ajax_template', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function() {
        
                    // create the first view
                    var initialContent = "<div class=\""+ajaxTemplateWidget.contentDivClass+"\"></div>";
        
                    // create a new jquery window
                    this.wnd = $('#content').window({
                        // change theese as needed
                       title: ajaxTemplateWidget.widgetTitle,
                       content: initialContent,
                       width: 625,
                       height: 400,
                       x: 30,
                       y: 15,
                       
                       // do NOT change theese
                       onMinimize:  this.onMinimize, 
                       onClose:  this.onClose,
                       checkBoundary: true,
                       maxWidth: $('#content').width(),
                       maxHeight: $('#content').height(),
                       bookmarkable: false
                    });
        
                    // load the first page upon start
                    ajaxTemplateWidget.load('/some_controller_name');
        
                } ,
                
    // --------------------------------------------------------------------------------------- 
    
    // set content in widgets div
    setContent: function(data)  
        {
            $('.'+ajaxTemplateWidget.contentDivClass).html(data);
        },
                
    // function that will load an url and set resulting data into specified div
    load: function(url)
        {
            
            // empty url?
            if (url == "")
            {
                show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            // show ajax spinner
            show_ajax_loader(null, ajaxTemplateWidget.contentDivClass);
            
            // load with ajax
            var loadURL = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
            $.ajax({
              type: 'GET',
              url: loadURL,
              success: function(data){
                    // set new content
                    ajaxTemplateWidget.setContent(data);
              },
              error: function(xhr, statusSTR, errorSTR) {
                    // display an error
                    show_ajax_error(null, ajaxTemplateWidget.contentDivClass, loadURL, ajaxTemplateWidget.errorIcon);
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
        show_ajax_loader(null, ajaxTemplateWidget.contentDivClass);
                 
        // post with ajax
        var loadURL = SITE_URL+'/widget/'+ajaxTemplateWidget.widgetName+url;
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
          success: function(data){
                // set new content
                ajaxTemplateWidget.setContent(data);
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
                show_ajax_error(null, ajaxTemplateWidget.contentDivClass, loadURL, ajaxTemplateWidget.errorIcon);
          }
       });
       
       return false;   
    },
    
    // shows a message (example in start.php)
    example_showMessage: function(message)
        {
            show_message(message);    
        }
    
};

   
// place widget in a namespace (javascript object simulates a namespace)
browserWidget = {

    contentDivClass: '',
    widgetTitle: '',
    widgetName: 'browser', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function() {
        
                    // create the first view
                    var initialContent = "<div class=\"browserTopBar\"><div class=\"browserInnerContent\">Enter a URL: <input type=\"text\" class=\"browserInput\" size=\"50\" /> <input type=\"button\" class=\"browserSubmitButton\" value=\"Go!\" onclick=\"browserWidget.load();\" /></div></div><div class=\"browserContent\"></div>";
        
                    // create a new jquery window
                    this.wnd = $('#content').window({
                        // change theese as needed
                       title: "Simple browser",
                       content: initialContent,
                       width: 800,
                       height: 450,
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
        
                } ,
                
    // --------------------------------------------------------------------------------------- 
      
    iframeHTML: "<iframe id=\"browserIFrame\" width=\"775\" height=\"375\" border=\"0\" frameborder=\"0\"></iframe>",
                
    // function that will load an url from the textinput
    load: function()
    {
        // get url from input
        var url = $('.browserInput').val();   
        
        // empty?
        if (url == "")
        {
            show_errormessage('Hey! :\'( You must enter a URL before submitting.');
            return;
        }
        
        // show ajax spinner
        browserWidget.showAjaxLoader();
        
        // load with ajax
        var loadURL = SITE_URL+"/widget/browser/main/get";
        $.ajax({
          type: 'POST',
          url: loadURL,
          data: {'url': url},
          success: function(data){
                // create an iframe
                $('.browserContent').html(browserWidget.iframeHTML);  
                
                // write result into iframe
                doc = document.getElementById('browserIFrame').contentWindow.document;
                doc.open();
                doc.write(data);
                doc.close();
          },
              error: function(xhr, statusSTR, errorSTR) {
                    // display an error (jquery ui)
                    var errorMessage = "<p class=\"ajaxTemplateWidget_Error\"><img src=\""+browserWidget.errorIcon+"\" width=\"35\" height=\"35\" />"+
                                       "Error: Unable to load the page at<br/><br/><small>"+loadURL+"</small></p>";

                    $('.browserContent').html(errorMessage);      
              }
       });
        
    },
    
    showAjaxLoader: function()
    {   
         // class frame_loading is from jquery.window 
         var container = $('.browserContent');
         container.html("<div class='frame_loading'>Loading...</div>");
         var loading = container.children(".frame_loading");
         loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px');
    }
    
};

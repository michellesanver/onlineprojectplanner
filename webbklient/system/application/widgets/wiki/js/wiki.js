
// place widget in a namespace (javascript object simulates a namespace)
wikiWidget = {

    pageContentDivClass: 'wiki_main_content',
    contentDivClass: 'wiki_content',
    widgetTitle: 'Wiki 1.0',
    widgetName: 'wiki', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function() {
        
                    // create the first view
                    var initialContent = "<div class=\""+wikiWidget.contentDivClass+"\"></div>";
        
                    // create a new jquery window
                    this.wnd = $('#content').window({
                        // change theese as needed
                       title: wikiWidget.widgetTitle,
                       content: initialContent,
                       width: 650,
                       height: 425,
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
                    wikiWidget.load('/pages');
        
                } ,
                
    // function that will load an url and set resulting data into specified div
    // if parameter into_page_content is set then the content is put into div.wiki_main_content 
    load: function(url, into_page_content)
        {
            
            // empty url?
            if (url == "")
            {
                show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            var whichDiv = wikiWidget.contentDivClass;
            if (into_page_content != undefined && into_page_content == true)
            {
                whichDiv = wikiWidget.pageContentDivClass;
            }
            
            // show ajax spinner
            show_ajax_loader(null, whichDiv);
            
            // load with ajax
            var loadURL = SITE_URL+'/widget/'+wikiWidget.widgetName+url;
            $.ajax({
              type: 'GET',
              url: loadURL,
              success: function(data){
                    // set new content if no error
                    if (data != "PAGE NOT FOUND" && data != "NOT AUTHORIZED")
                    {
                        $('.'+whichDiv).html(data);
                    }
                    else if (data == "PAGE NOT FOUND")
                    {
                        var errorHtml = '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>The requested Wiki-page was not found.';
                        $('.'+whichDiv).html(errorHtml);     
                    }
                    else if (data == "NOT AUTHORIZED")
                    {
                        var errorHtml = '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>Authorization failed! You must be logged in.';
                        $('.'+whichDiv).html(errorHtml);     
                    }
              },
              error: function(xhr, statusSTR, errorSTR) {
                    // display an error
                    show_ajax_error(null, whichDiv, loadURL, wikiWidget.errorIcon);
              }
           });      
           
           return false; 
        },
    
    // post data
    // if parameter into_page_content is set then the content is put into div.wiki_main_content    
    post: function(formID, url, into_page_content)
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
        
        var whichDiv = wikiWidget.contentDivClass;
        if (into_page_content != undefined && into_page_content == true)
        {
            whichDiv = wikiWidget.pageContentDivClass;
        }
        
        // show ajax spinner
        show_ajax_loader(null, whichDiv);
                 
        // post with ajax
        var loadURL = SITE_URL+'/widget/'+wikiWidget.widgetName+url;
        $.ajax({
          type: 'POST',
          data: postdata,
          url: loadURL,
          success: function(data){
                    // set new content if no error
                    if (data != "PAGE NOT FOUND" && data != "NOT AUTHORIZED")
                    {
                        $('.'+whichDiv).html(data);
                    }
                    else if (data == "PAGE NOT FOUND")
                    {
                        var errorHtml = '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>The requested Wiki-page was not found.';
                        $('.'+whichDiv).html(errorHtml);     
                    }
                    else if (data == "NOT AUTHORIZED")
                    {
                        var errorHtml = '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>Authorization failed! You must be logged in.';
                        $('.'+whichDiv).html(errorHtml);     
                    }
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
                show_ajax_error(null, whichDiv, loadURL, wikiWidget.errorIcon);
          }
       });
       
       return false;   
    },
    
    // search wiki by word or tag
    search: function(word, tag, resultDivClass) {
        
        // create an ajax spinner
         var loadingHTML = "<div class='frame_loading'>Searching...</div>"; 
         var container = $('.'+resultDivClass);
         container.html(loadingHTML);
         var loading = container.children(".frame_loading");
         loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px'); 
         
        // send post
        var loadURL = SITE_URL+'/widget/'+wikiWidget.widgetName+'/pages/search';
        $.ajax({
          type: 'POST',
          data: {'word': word, 'tag': tag},
          url: loadURL,
          success: function(data){
                    // set new content if no error
                    if (data != "PAGE NOT FOUND" && data != "NOT AUTHORIZED")
                    {
                        $('.'+resultDivClass).html(data);
                    }
                    else if (data == "PAGE NOT FOUND")
                    {
                        var errorHtml = '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>The requested Wiki-page was not found.';
                        $('.'+wikiWidget.pageContentDivClass).html(errorHtml);     
                    }
                    else if (data == "NOT AUTHORIZED")
                    {
                        var errorHtml = '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>Authorization failed! You must be logged in.';
                        $('.'+wikiWidget.pageContentDivClass).html(errorHtml);     
                    }
          },
          error: function(xhr, statusSTR, errorSTR) {
                // display an error
                show_ajax_error(null, wikiWidget.pageContentDivClass, loadURL, wikiWidget.errorIcon);
          }
       });
       
       return false; 
         
    }
} 

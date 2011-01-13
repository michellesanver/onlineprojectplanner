
// place widget in a namespace (javascript object simulates a namespace)
wikiWidget = {

    pageContentDivClass: 'wiki_main_content',
    contentDivClass: 'wiki_content',
    widgetTitle: 'Wiki 1.1',
    widgetName: 'wiki', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId) {

            // create the first view
            var initialContent = "<div class=\""+wikiWidget.contentDivClass+"\"></div>";


            // set options for window
            var windowOptions = {
                // change theese as needed
                title: wikiWidget.widgetTitle,
                content: initialContent, 
                width: 650,
                height: 425,
                x: 30,
                y: 15
            };

            // create window
            Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, wikiWidget.partialContentDivClass);

            // load the first page upon start
            var loadFirstPage = SITE_URL+'/widget/' + wikiWidget.widgetName + '/pages';
            ajaxRequests.load(loadFirstPage, "wikiWidget.setContent", "wikiWidget.setAjaxError");
        
     },
     
    // set content in widgets div, called from the ajax request
    setContent: function(data) {
            // The success return function, the data must be unescaped befor use.
            // This is due to ILLEGAL chars in the string.
            Desktop.setWidgetContent(unescape(data));
    },

    // set partial content in widgets div, called from the ajax request
    setPartialContent: function(data) {
            // set currentpartial to to the classname
            this.currentPartial = wikiWidget.partialContentDivClass;
        
            // The success return function, the data must be unescaped befor use.
            // This is due to ILLEGAL chars in the string.
            Desktop.setWidgetPartialContent(this.currentPartial, unescape(data));
            this.currentPartial = null;
    },
                
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL) {
            Desktop.show_ajax_error_in_widget(loadURL);
    },           
                
                
    
    // variable to save incoming value (link) for partial or not            
    loadData_Into_Partial: false,
                
    // function that will load an url and set resulting data into specified div
    // if parameter into_page_content is set then the content is put into partial
    load: function(url, into_page_content) {
            
            // empty url?
            if (url == "") {
                wikiWidget.setAjaxError('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            // load data into partial content?
            if (into_page_content != undefined && into_page_content == true) {
                wikiWidget.loadData_Into_Partial = true;
            }  else {
                wikiWidget.loadData_Into_Partial = false;
            }
            
            // do ajax request
           var urlToLoad = SITE_URL+'/widget/' + wikiWidget.widgetName + url;
           ajaxRequests.load(urlToLoad, "wikiWidget.loadCallback", "wikiWidget.setAjaxError"); 
           
           return false; 
    },
    
    // callback-function to load; handle messages and status
    loadCallback: function(data) {
        
            var newHTML = "";
            var hasError = false;
            
            // set new content if no error
            if (data != "PAGE%20NOT%20FOUND" && data != "NOT%20AUTHORIZED") {
                newHTML = data;
            }
            else if (data == "PAGE%20NOT%20FOUND") {
               newHTML = '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>The requested Wiki-page was not found.';     
               hasError = true;
            }
            else if (data == "NOT%20AUTHORIZED") {
                newHTML = '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>Authorization failed! You must be logged in.';     
                hasError = true;
            }
                        
            // load into partial?
            if (wikiWidget.loadData_Into_Partial == true || hasError == true) {
                wikiWidget.setPartialContent(newHTML);
            } else {
                wikiWidget.setContent(newHTML);    
            }
    },
    
    // post data
    // if parameter into_page_content is set then the content is put into div.wiki_main_content    
    post: function(formID, url, into_page_content)
    {
       /* var postdata = $('#'+formID ).serialize();
      
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
       
       return false;     */
    },
    
    // search wiki by word or tag
    search: function(word, tag, resultDivClass, appendTitle) { 
         
        // create an ajax spinner
     /*    var loadingHTML = "<div class='frame_loading'>Searching...</div>"; 
         var container = $('.'+resultDivClass);
         container.html(loadingHTML);
         var loading = container.children(".frame_loading");
         loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px'); 
           
        // send post
        var loadURL = SITE_URL+'/widget/'+wikiWidget.widgetName+'/pages/search';
        $.ajax({
          type: 'POST',
          data: {'word': word, 'tag': tag },
          url: loadURL,
          success: function(data){
                    // set new content if no error
                    if (data != "PAGE NOT FOUND" && data != "NOT AUTHORIZED")
                    {
                        if (appendTitle != undefined && appendTitle == true)
                        {
                            data = '<h1>Search result</h1>'+data;    
                        }
                        
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
       
       return false;      */
         
    }
} 

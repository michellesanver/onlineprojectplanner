
// place widget in a namespace (javascript object simulates a namespace)
wikiWidget = {

    wiki_instance_id: null,
    
    pageContentDivClass: 'wiki_main_content',
    contentDivClass: 'wiki_content',
    widgetTitle: 'Wiki 1.1',
    widgetName: 'wiki', // also name of folder
    errorIcon: BASE_URL+'images/backgrounds/erroricon.png',
    
    currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId) {
            // save unique id for database
            wikiWidget.wiki_instance_id = project_widget_id;
        
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
            Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, wikiWidget.pageContentDivClass);

            // load the first page upon start
            var loadFirstPage = SITE_URL+'/widget/' + wikiWidget.widgetName + '/pages/index/' + wikiWidget.wiki_instance_id;
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
            // The success return function, the data must be unescaped befor use.
            // This is due to ILLEGAL chars in the string.
            Desktop.setWidgetPartialContent(wikiWidget.pageContentDivClass, unescape(data));
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
            if (url == undefined || url == "") {
                Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
                return;
            }
            
            // load data into partial content?
            if (into_page_content != undefined && into_page_content == true) {
                wikiWidget.loadData_Into_Partial = true;
            }  else {
                wikiWidget.loadData_Into_Partial = false;
            }
           
            // prepare url; add instance id last to request
            var urlToLoad = SITE_URL + '/widget/' + wikiWidget.widgetName + url + '/' + wikiWidget.wiki_instance_id; 
            
            
            // do ajax request
           if (wikiWidget.loadData_Into_Partial==false) {
                // no partial
                ajaxRequests.load(urlToLoad, "wikiWidget.load_callback", "wikiWidget.setAjaxError"); 
           } else {
               // use partial
               wikiWidget.show_partial_ajax_loader();
               ajaxRequests.load(urlToLoad, "wikiWidget.load_callback", "wikiWidget.setAjaxError", true); 
           }
           
           return false; 
    },
    
    // callback-function to load; handle messages and status
    load_callback: function(data) {
        
            var newHTML = "";
            var hasError = false;
            
            // set new content if no error
            if (data != "PAGE%20NOT%20FOUND" && data != "NOT%20AUTHORIZED") {
                newHTML = data;
            }
            else if (data == "PAGE%20NOT%20FOUND") {
                   // get content for page not found and set error
               newHTML = wikiWidget.get_content_page_not_found();
               hasError = true;
            }
            else if (data == "NOT%20AUTHORIZED") {
                // get content for not authorized and set error 
                newHTML = wikiWidget.get_content_not_authorized();
                hasError = true;
            }
                        
            // load into partial?
            if (wikiWidget.loadData_Into_Partial == true || hasError == true) {
                wikiWidget.setPartialContent(newHTML);
            } else {
                wikiWidget.setContent(newHTML);    
            }
    },
    
    // create an ajax loader for partial
    show_partial_ajax_loader: function()  {
        container = $('.' + wikiWidget.pageContentDivClass);
        var loadingHTML = "<div class='frame_loading'>Loading...</div>"; 
        container.html(loadingHTML);
        var loading = container.children(".frame_loading");
        loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px');
    },
    
    // variable to save incoming value (link) for partial or not            
    postData_Into_Partial: false,
    
    // post data for create a new wikipage
    post: function(formClass, url, into_page_content) {
      
        // catching the form data
        var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();
        
        // empty url?
        if (url == undefined || url == "") {
            Desktop.show_errormessage('Hey! :\'( Load data from which URL!? *confused*');
            return;
        }
        // empty postdata?
        else if (postdata == "") {
            Desktop.show_errormessage('Hey! :\'( No data found to submit? *confused*');
            return;
        }
       
        // load data into partial content?
        if (into_page_content != undefined && into_page_content == true) {
            wikiWidget.postData_Into_Partial = true;
        }  else {
            wikiWidget.postData_Into_Partial = false;
        }
       
        // prepare url; add instance id 
        url = SITE_URL+'/widget/'+wikiWidget.widgetName+url+ '/' + wikiWidget.wiki_instance_id;
               
        // show ajax spinner
        wikiWidget.show_partial_ajax_loader();               
                                
        // send request
        if (wikiWidget.postData_Into_Partial==false) {
            // no partial
            ajaxRequests.post(postdata, url, 'wikiWidget.post_callback', 'wikiWidget.setAjaxError');  
        } else {
            ajaxRequests.post(postdata, url, 'wikiWidget.post_callback', 'wikiWidget.setAjaxError', true); 
        }
        
       return false; 
    },
    
    
    // function used from php backend to show a page not found-error
    show_page_not_found:function() {
        // get content and show in partial       
        var newHTML = wikiWidget.get_content_page_not_found();
        wikiWidget.setPartialContent(newHTML);   
    },
    
    
    // callback for creating a new page
    post_callback: function(data) {
            var newHTML = "";
            var hasError = false;
            
            // set new content if no error
            if (data != "PAGE%20NOT%20FOUND" && data != "NOT%20AUTHORIZED") {
                newHTML = data;
            }
            else if (data == "PAGE%20NOT%20FOUND") {
                  // get content for page not found and set error
               newHTML = wikiWidget.get_content_page_not_found();
               hasError = true;
            }
            else if (data == "NOT%20AUTHORIZED") {
                 // get content for not authorized and set error 
                newHTML = wikiWidget.get_content_not_authorized();
                hasError = true;
            }
                        
            // load into partial?
            if (wikiWidget.postData_Into_Partial == true || hasError == true) {
                wikiWidget.setPartialContent(newHTML);
            } else {
                wikiWidget.setContent(newHTML);    
            } 
    },
    
    // search wiki by word or tag
    search: function(word, tag, resultDivClass) { 
        
        // get data to post
        var postdata = {'word': word, 'tag': tag };
         
        // prepare url and also add instance id 
        url = SITE_URL+'/widget/'+wikiWidget.widgetName+ '/pages/search/' + wikiWidget.wiki_instance_id;
               
        // show ajax spinner
        wikiWidget.show_partial_ajax_loader();               
                                
        // send request
        ajaxRequests.post(postdata, url, 'wikiWidget.search_callback', 'wikiWidget.setAjaxError', true); 
 
       return false; 
         
    },
    
    // callback for search
    search_callback: function(data) {
            var newHTML = "";
            var hasError = false;
            
            // set new content if no error
            if (data != "PAGE%20NOT%20FOUND" && data != "NOT%20AUTHORIZED") {
                
                // add titl?
                data = '<h1>Search result</h1>'+data;     

                // set content to view 
                newHTML = data;
            }
            else if (data == "PAGE%20NOT%20FOUND") {
                // get content for page not found and set error
               newHTML = wikiWidget.get_content_page_not_found();
               hasError = true;
            }
            else if (data == "NOT%20AUTHORIZED") {
                // get content for not authorized and set error 
                newHTML = wikiWidget.get_content_not_authorized();
                hasError = true;
            }
                        
            // show result
            wikiWidget.setPartialContent(newHTML);

    },
    
    
    // -------------------------------------------------------------------------------------------------------------
    // helpers content/messages
    
    get_content_page_not_found: function() {
        return '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>The requested Wiki-page was not found.';
    },
    
    get_content_not_authorized: function() {
        return '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+wikiWidget.errorIcon+'" /></span>Authorization failed! You must be logged in.';             
    }
    
} 

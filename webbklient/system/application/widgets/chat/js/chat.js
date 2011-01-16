chatWidget = {

    // Widget specific settings

    partialContentDivClass: 'chat_partial',
    widgetTitle: 'Chat 1.0 (Beta)',
    widgetName: 'chat',
    currentPartial: null,
    
    // Function that will be called upon start (REQUIRED)

    open: function(project_widget_id, widgetIconId) {

        // Set options for window

        var windowOptions = {

            // change theese as needed
            title: chatWidget.widgetTitle,
            width: 650,
            height: 400,
            x: 0,
            y: 0,
            allowSettings: false,
            resizable: false,
            scrollable: false
        };

        // Create window

        Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId);
        Desktop.selectedWindowId.resizable = false;

        // Load the first page upon start

        var loadFirstPage = SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/index/';

        ajaxRequests.load(loadFirstPage, "chatWidget.setContent", "chatWidget.setAjaxError");

        // Set overflow hidden

    },
		
   /*
    * The following functions are common for all widgets
    * -
    * -
    */

    // Set content in widgets div, called from the ajax request

    setContent: function(data) {

        // The success return function, the data must be unescaped befor use
        // This is due to ILLEGAL chars in the string

        Desktop.setWidgetContent(unescape(data));

    },

    // Set partial content in widgets div, called from the ajax request

    setPartialContent: function(data) {

        // The success return function, the data must be unescaped befor use
        // This is due to ILLEGAL chars in the string

        Desktop.setWidgetPartialContent(this.currentPartial, unescape(data));
        this.currentPartial = null;

    },
    
    // Set error-message in widgets div, called from the ajax request

    setAjaxError: function(loadURL) {

        Desktop.show_ajax_error_in_widget(loadURL);

    },
    
    // Shows a message

    example_showMessage: function(message) {

        Desktop.show_message(message);

    },

    // Wrapper-function that easily can be used inside views from serverside

    loadURL: function(url) {

        // Prepare url

        url = SITE_URL+'/widget/'+chatWidget.widgetName+url;

        // Send request

        ajaxRequests.load(url, 'chatWidget.setContent', 'chatWidget.setAjaxError');

    },
		
    // Loads a ajaxrequest to specific partialclass, in this case "chat_partial"

    loadURLtoPartialTest: function(url) {

        // Prepare url

        url = SITE_URL+'/widget/'+chatWidget.widgetName+url;

        // Set currentpartial to to the classname

        this.currentPartial = chatWidget.partialContentDivClass;

        // Send request, last parameter = true if this is a partial call.
        // Will skip the loading image

        ajaxRequests.load(url, 'chatWidget.setPartialContent', 'chatWidget.setAjaxError', true);

    },
		
    // Wrapper-function that easily can be used inside views from serverside

    postURL: function(formClass, url) {

        // Prepare url

        url = SITE_URL+'/widget/'+chatWidget.widgetName+url;
				
        // Catching the form data

        var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();

        // Send request

        ajaxRequests.post(postdata, url, 'chatWidget.setContent', 'chatWidget.setAjaxError');

    }

    // ...

};

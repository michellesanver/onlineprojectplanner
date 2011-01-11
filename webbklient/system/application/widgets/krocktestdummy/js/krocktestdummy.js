
krocktestdummyWidget = {

    pageContentDivClass: 'krocktestdummy_main_content',
    contentDivClass: 'krocktestdummy_content',
    
    widgetTitle: 'KrockTestDummy',
    widgetName: 'krocktestdummy', // also name of folder
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId) {
    
        var windowOptions = {
             title: krocktestdummyWidget.widgetTitle,
             width: 300,
             height: 200,
             x: 10,
             y: 15,
             content: krocktestdummyWidget.getInitialContent()
         };

        Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, krocktestdummyWidget.partialContentDivClass);
                    
    },
    
    // set content in widgets div, called from the ajax request
   setContent: function(data) {
        // The success return function, the data must be unescaped befor use.
        // This is due to ILLEGAL chars in the string.    
        Desktop.setWidgetContent(unescape(data));
    },
    
    loadSecond: function() {
        krocktestdummyWidget.setContent( krocktestdummyWidget.getSecondContent() );  
    },
   
    loadInitial: function() {
        krocktestdummyWidget.setContent( krocktestdummyWidget.getInitialContent() );  
    },
    
    // ----------------------------------------------------------------------------------------------------------------------
                     
    getInitialContent: function() {
        return '<h1>Initial content</h1><p>Lorem ipsum hejsan hoppsan kalle kule var här och skrev lite grann....</p><a href="javascript:void(0);" onclick="krocktestdummyWidget.loadSecond();">Load another page (only JS)</a>';    
    },
    
    getSecondContent: function() {
        return '<h1>Second content</h1><p>Lorem ipsum....</p><a href="javascript:void(0);" onclick="krocktestdummyWidget.loadInitial();">Load initial page (only JS)</a>';            
    }    
    
}

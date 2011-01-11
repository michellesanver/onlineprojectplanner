
krocktestdummyWidget = {

    counter: 0,
    
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
        
        setInterval('krocktestdummyWidget.updateCounter()', 1000);
    },
    
    // set content in widgets div, called from the ajax request
   setContent: function(data) {
        // The success return function, the data must be unescaped befor use.
        // This is due to ILLEGAL chars in the string.    
        Desktop.setWidgetContent(unescape(data));
    },
    
    updateCounter: function() {
        krocktestdummyWidget.counter++;
        krocktestdummyWidget.setContent( krocktestdummyWidget.getInitialContent() );    
    },
    
    // ----------------------------------------------------------------------------------------------------------------------
                     
    getInitialContent: function() {
        return '<h1>Initial content</h1><p>Lorem ipsum hejsan hoppsan kalle kule var här och skrev lite grann....</p> <p>counter: '+krocktestdummyWidget.counter;    
    }   
    
}

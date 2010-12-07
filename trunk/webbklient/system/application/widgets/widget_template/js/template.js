
// place widget in a namespace (javascript object simulates a namespace)
templateWidget = {

    // variable for window (DO NOT CHANGE - REQUIRED)
    wnd: null, 
    
    // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
    onMinimize: null, 
    onClose:null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function() {
        
                    // create a new jquery window
                    templateWidget.wnd = $('#content').window({
                        // change theese as needed
                       title: "Widget template 1.0",
                       url: SITE_URL+"/widget/widget_template/main",
                       width: 600,
                       height: 400,
                       x: 30,
                       y: 15,
                       
                       // set namespace as needed
                       onMinimize:  templateWidget.onMinimize, 
                       onClose:  templateWidget.onClose,
                       
                       // do NOT change theese
                       checkBoundary: true,
                       maxWidth: $('#content').width(),
                       maxHeight: $('#content').height(),
                       bookmarkable: false
                    });
        
                },
                
    example_setContent: function() {
        // example function that will set content
        // in a opened window    
        
        var html = "<h1>View hardcoded in javascript</h1>"+
                    "<p>Lorem ipsum etc etc :P</p>"+
                    "<p><a href=\"javascript:void(0);\" onclick=\"window.back(-1);\"></p>";
                    
        this.wnd.setContent(html);
    }
    
};

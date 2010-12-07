
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
                    this.wnd = $('#content').window({
                        // change theese as needed
                       title: "Widget template 1.0",
                       url: SITE_URL+"/widget/widget_template/main",
                       width: 600,
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
        
                },
                
    example_showMessage: function(message) {
        
        // call to a common global function
        show_message(message);
    }
    
};

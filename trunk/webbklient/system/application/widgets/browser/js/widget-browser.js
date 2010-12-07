
// lägg i ett eget namespace
browserWidget = {

    // variabel för fönstret
    wnd: null, 
    
    // callbacks som sätts globalt (common.js)   
    onMinimize: null, 
    onClose:null,
    
    openBrowser: function()
                {
                    // #content är global och ligger i designen = "desktop"
                    var maxheight =  $('#content').height();
                    var maxwidth =  $('#content').width();
                    
                    // skapa nytt fönster med jquery window
                    browserWidget.wnd = $('#content').window({
                       title: "Simple browser",
                       url: SITE_URL+"/widget/browser/main",
                       content: "",
                       checkBoundary: true,
                       width: 600,
                       height: 400,
                       maxWidth: maxwidth,
                       maxHeight: maxheight,
                       x: 30,
                       y: 15,
                       bookmarkable: false,
                       onMinimize:  browserWidget.onMinimize,
                       onClose:  browserWidget.onClose
                    });    
                       
                }

};



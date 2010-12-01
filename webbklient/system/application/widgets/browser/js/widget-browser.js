
function openBrowser()
{
    var maxheight =  $('#content').height();
    var maxwidth =  $('#content').width();
    
    $('#content').window({
       title: "Simple browser",
       url: SITE_URL+"/widget/browser/main",
       checkBoundary: true,
        width: 600,
        height: 400,
        maxWidth: maxwidth,
        maxHeight: maxheight,
        x: 30,
        y: 15              
    });    
   
       
}


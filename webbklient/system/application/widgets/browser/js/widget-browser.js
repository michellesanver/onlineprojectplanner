
function openBrowser()
{
    var t = "";
    
    $.window({
       title: "Simple browser",
       url: SITE_URL+"/widget/browser/main",
       draggable: true,
       resizable: true,
       maximizable: true,
       minimizable: true
    });    
   
       
}


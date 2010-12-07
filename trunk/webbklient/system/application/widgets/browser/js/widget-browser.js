
browserWnd = null;
function openBrowser()
{
    var maxheight =  $('#content').height();
    var maxwidth =  $('#content').width();
    
    browserWnd = $('#content').window({
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
		onMinimize: onMinimizeCallback
    });    
       
}


function onMinimizeCallback()
{
	browserWnd.close();
	browserWnd = null;
	alert('TEST: onMinimze');
}

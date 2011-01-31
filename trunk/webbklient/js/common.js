
// -----------------------------------------------------------------------------------------------------------
// set height of #content on load so maximize will work properly

$(document).ready(function() {
    // set size on load
    setContentSize();
    
    // browser resize
    $(window).resize(function() {
        setContentSize();
    });
});

function setContentSize()
{
    var docHeight = $(document).height();
    var topBarHeight = $('#topbar').height();
    var wBarHeight = $('#widget_bar').height();
    var contentHeight;

    if($('#topbar').is(':visible') == false && $('#widget_bar').is(':visible') == false)
    {
        contentHeight = docHeight - 11; // 11 is for margins
    }
    else if($('#topbar').is(':visible') == false && $('#widget_bar').is(':visible') != false)
    {
        contentHeight = (docHeight - wBarHeight) - 21; // 21 is for margins
    }
    else if($('#widget_bar').is(':visible') == false)
    {
        contentHeight = (docHeight - topBarHeight) - 11; // 11 is for margins
    }
    else
    {
        contentHeight = ((docHeight - topBarHeight) - wBarHeight) - 21; // 21 is for margins
    }
    
    $('#desktop').css('height',contentHeight+'px');  
}



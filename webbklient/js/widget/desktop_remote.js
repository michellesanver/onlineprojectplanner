
DesktopRemote = {

    topBar: '#topbar',
    topBarHeight: null,
    widgetBar: '#widget_bar',
    widgetBarHeight: null,
    desktop: '#desktop',
    desktopHeight: null,

    init:function() {

        $('.dektop_remote_object').bind('click', function() {

            DesktopRemote.topBarHeight = $(DesktopRemote.topBar).height();
            DesktopRemote.widgetBarHeight = $(DesktopRemote.widgetBar).height();
            DesktopRemote.desktopHeight = $(DesktopRemote.desktop).height();

            var order = $(this).attr('id');

            // Lock and load

            if($(this).hasClass('locked') == false)
            {
                $(this).addClass('locked');

                if(order == 'single_up' || order == 'single_down' )
                {
                    DesktopRemote.handleSingle(order);
                }
                else if(order == 'double_up' || order == 'double_down' )
                {
                    DesktopRemote.handleDouble(order);
                }
            }

        });

    },

    handleSingle:function(order) {

        if(order == 'single_up')
        {
            $(DesktopRemote.widgetBar).hide(500);

            $(DesktopRemote.desktop).delay(500).animate({'height': (DesktopRemote.desktopHeight + DesktopRemote.widgetBarHeight) + 10});

            $('#single_up').delay(1000).removeClass('locked').attr('id', 'single_down');

        }
        else if(order == 'single_down')
        {
            $(DesktopRemote.desktop).animate({'height': (DesktopRemote.desktopHeight - DesktopRemote.widgetBarHeight) - 10});

            $(DesktopRemote.widgetBar).delay(500).show(500);

            $('#single_down').delay(1500).removeClass('locked').attr('id', 'single_up');
        }

    },

    handleDouble:function(order) {

        alert('not yet implemented...')

    }


}
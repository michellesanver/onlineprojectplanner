
DesktopRemote = {

    remoteWrapper: '#desktop_remote_wrapper',
    topBar: '#topbar',
    topBarHeight: null,
    widgetBar: '#widget_bar',
    widgetBarHeight: null,
    desktop: '#desktop',
    desktopHeight: null,
    widgetPanel: '.window_panel',

    init:function() {

        $('.dektop_remote_object').tooltip({track: true,
        delay: 0,
        opacity: 0.5,
        fade: 250,
        top: -40,
        left: 0});

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

            $(DesktopRemote.widgetPanel).each(function(index) {

                var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                var newWidgetPanelTop = (widgetPanelTop - DesktopRemote.widgetBarHeight) - 10;

                $(this).delay(500).animate({'top': newWidgetPanelTop});

            });

            $('#single_up').delay(1000).removeClass('locked').attr('id', 'single_down');
        }
        else if(order == 'single_down')
        {
            $(DesktopRemote.desktop).animate({'height': (DesktopRemote.desktopHeight - DesktopRemote.widgetBarHeight) - 10});

            $(DesktopRemote.widgetBar).delay(500).show(500);

            $(DesktopRemote.widgetPanel).each(function(index) {

                var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                var newWidgetPanelTop = (widgetPanelTop + DesktopRemote.widgetBarHeight) + 10;

                $(this).delay(500).animate({'top': newWidgetPanelTop});

            });

            $('#single_down').delay(1500).removeClass('locked').attr('id', 'single_up');
        }

    },

    handleDouble:function(order) {

        if(order == 'double_up')
        {
            if($(DesktopRemote.widgetBar).is(':visible') != false)
            {
                $(DesktopRemote.topBar).hide(500);
                $(DesktopRemote.widgetBar).hide(500);

                $(DesktopRemote.desktop).delay(500).animate({'height': ((DesktopRemote.desktopHeight + DesktopRemote.topBarHeight) + DesktopRemote.widgetBarHeight) + 10});

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = ((widgetPanelTop - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 10;

                    $(this).delay(500).animate({'top': newWidgetPanelTop});

                });
            }
            else
            {
                $(DesktopRemote.topBar).hide(500);

                $(DesktopRemote.desktop).delay(500).animate({'height': DesktopRemote.desktopHeight + DesktopRemote.topBarHeight});

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = widgetPanelTop - DesktopRemote.topBarHeight;

                    $(this).delay(500).animate({'top': newWidgetPanelTop});

                });
            }

            $('#double_up').delay(1000).removeClass('locked').attr('id', 'double_down');
            $('#single_up').delay(1000).attr('id', 'single_down');

        }
        else if(order == 'double_down')
        {
            if($(DesktopRemote.widgetBar).is(':visible') != false)
            {
                $(DesktopRemote.desktop).animate({'height': (DesktopRemote.desktopHeight - DesktopRemote.topBarHeight)});

                $(DesktopRemote.topBar).delay(500).show(500);

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = widgetPanelTop + DesktopRemote.topBarHeight;

                    $(this).delay(500).animate({'top': newWidgetPanelTop});

                });
            }
            else
            {
                $(DesktopRemote.desktop).animate({'height': ((DesktopRemote.desktopHeight - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 10});

                $(DesktopRemote.topBar).delay(500).show(500);
                $(DesktopRemote.widgetBar).delay(500).show(500);

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = ((widgetPanelTop + DesktopRemote.topBarHeight) + DesktopRemote.widgetBarHeight) + 10;

                    $(this).delay(500).animate({'top': newWidgetPanelTop});

                });
            }

            $('#double_down').delay(1500).removeClass('locked').attr('id', 'double_up');
            $('#single_down').delay(1500).attr('id', 'single_up');
        }

    }


}
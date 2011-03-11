
DesktopRemote = {

    documentOriginalHeight: null,
    remoteWrapper: '#desktop_remote_wrapper',
    topBar: '#topbar',
    topBarHeight: null,
    widgetBar: '#widget_bar',
    widgetBarHeight: null,
    desktop: '#desktop',
    desktopHeight: null,
    widgetPanel: '.window_panel',

    init:function() {

        // Handle tooltip

        $('.dektop_remote_object').tooltip({track: true,
        delay: 0,
        opacity: 0.5,
        fade: 250,
        top: -40,
        left: 20,
        extraClass: 'desktop_remote_tooltip'});

        // Handle original height on init

        DesktopRemote.documentOriginalHeight = $(document).height();

        // Handle original height on resize

        $(window).resize(function() {

            DesktopRemote.documentOriginalHeight = $(document).height();

        });

        // Bind triggers

        $('.dektop_remote_object').bind('click', function() {

            DesktopRemote.topBarHeight = $(DesktopRemote.topBar).outerHeight(true);
            DesktopRemote.widgetBarHeight = $(DesktopRemote.widgetBar).outerHeight(true);
            DesktopRemote.desktopHeight = $(DesktopRemote.desktop).outerHeight(true);

            // Get order

            var order = $(this).attr('id');

            // Lock and load

            if($(this).hasClass('locked') == false)
            {
                $('.dektop_remote_object').addClass('locked');
                $(DesktopRemote.remoteWrapper).animate({'margin-left': -50});

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

        // Single (Widget Bar) up function

        if(order == 'single_up')
        {
            // Hide Widget Bar

            $(DesktopRemote.widgetBar).hide(500);

            setTimeout(function() {

                // Set new dektop height

                $(DesktopRemote.desktop).animate({'height': (DesktopRemote.desktopHeight + DesktopRemote.widgetBarHeight) - 20});

                // Animate widgets

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = (widgetPanelTop - DesktopRemote.widgetBarHeight);

                    $(this).animate({'top': newWidgetPanelTop});

                });

            }, 500);

            // Unlock

            setTimeout(function() {

                $('#single_up').attr('id', 'single_down');
                $('.dektop_remote_object').removeClass('locked');
                $(DesktopRemote.remoteWrapper).animate({'margin-left': 0});

            }, 1000);
        }

        // Single (Widget Bar) down function

        else if(order == 'single_down')
        {
            // Reset desktop height

            $(DesktopRemote.desktop).height(0);

            // Show Widget Bar

            $(DesktopRemote.widgetBar).show(500);

            // Animate widgets

            $(DesktopRemote.widgetPanel).each(function(index) {

                var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                var newWidgetPanelTop = (widgetPanelTop + DesktopRemote.widgetBarHeight);

                $(this).animate({'top': newWidgetPanelTop});

            });

            // Set new dektop height

            setTimeout(function() {

                if($(DesktopRemote.topBar).is(':visible') != false)
                {

                    if($(document).height() > DesktopRemote.documentOriginalHeight)
                    {
                        $(DesktopRemote.desktop).animate({'height': (($(document).height() - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 10});
                    }
                    else
                    {
                        $(DesktopRemote.desktop).animate({'height': ((DesktopRemote.documentOriginalHeight - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 20});
                    }
                }
                else
                {
                    if($(document).height() > DesktopRemote.documentOriginalHeight)
                    {
                        $(DesktopRemote.desktop).animate({'height': ($(document).height() - DesktopRemote.widgetBarHeight) - 10});
                    }
                    else
                    {
                        $(DesktopRemote.desktop).animate({'height': (DesktopRemote.documentOriginalHeight - DesktopRemote.widgetBarHeight) - 20});
                    }
                }

            }, 800);

            // Unlock

            setTimeout(function() {

                $('#single_down').attr('id', 'single_up');
                $('.dektop_remote_object').removeClass('locked');
                $(DesktopRemote.remoteWrapper).animate({'margin-left': 0});

            }, 1000);
        }

    },

    handleDouble:function(order) {

        // Double (Top Bar / Widget Bar) up function

        if(order == 'double_up')
        {
            if($(DesktopRemote.widgetBar).is(':visible') != false)
            {
                // Hide Top Bar and Widget Bar

                $(DesktopRemote.topBar).hide(500);
                $(DesktopRemote.widgetBar).hide(500);

                setTimeout(function() {

                    // Set new dektop height

                    $(DesktopRemote.desktop).animate({'height': ((DesktopRemote.desktopHeight + DesktopRemote.topBarHeight) + DesktopRemote.widgetBarHeight) - 20});

                    // Animate widgets

                    $(DesktopRemote.widgetPanel).each(function(index) {

                        var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                        var newWidgetPanelTop = ((widgetPanelTop - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight);

                        $(this).animate({'top': newWidgetPanelTop});

                    });

                }, 500);
            }
            else
            {
                // Hide Top Bar

                $(DesktopRemote.topBar).hide(500);

                setTimeout(function() {

                    // Set new dektop height

                    $(DesktopRemote.desktop).animate({'height': DesktopRemote.desktopHeight + DesktopRemote.topBarHeight} - 10);

                    // Animate widgets

                    $(DesktopRemote.widgetPanel).each(function(index) {

                        var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                        var newWidgetPanelTop = widgetPanelTop - DesktopRemote.topBarHeight;

                        $(this).animate({'top': newWidgetPanelTop});

                    });

                }, 500);
            }

            // Unlock

            setTimeout(function() {

                $('#double_up').attr('id', 'double_down');
                $('#single_up').attr('id', 'single_down');
                $('.dektop_remote_object').removeClass('locked');
                $(DesktopRemote.remoteWrapper).animate({'margin-left': 0});

            }, 1000);
        }

        // Double (Top Bar / Widget Bar) down function

        else if(order == 'double_down')
        {
            // Reset desktop height

            $(DesktopRemote.desktop).height(0);

            if($(DesktopRemote.widgetBar).is(':visible') != false)
            {
                // Show Top Bar

                $(DesktopRemote.topBar).show(500);

                // Animate widgets

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = widgetPanelTop + DesktopRemote.topBarHeight;

                    $(this).animate({'top': newWidgetPanelTop});

                });

                // Set new dektop height

                setTimeout(function() {

                    if($(document).height() > DesktopRemote.documentOriginalHeight)
                    {
                        $(DesktopRemote.desktop).animate({'height': (($(document).height() - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 10});
                    }
                    else
                    {
                        $(DesktopRemote.desktop).animate({'height': ((DesktopRemote.documentOriginalHeight - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 20});
                    }

                }, 800);
            }
            else
            {
                // Show Top Bar adn Widget Bar

                $(DesktopRemote.topBar).show(500);
                $(DesktopRemote.widgetBar).show(500);

                // Animate widgets

                $(DesktopRemote.widgetPanel).each(function(index) {

                    var widgetPanelTop = parseInt($(this).css('top').replace('px', ''));
                    var newWidgetPanelTop = ((widgetPanelTop + DesktopRemote.topBarHeight) + DesktopRemote.widgetBarHeight);

                    $(this).animate({'top': newWidgetPanelTop});

                });

                // Set new dektop height

                setTimeout(function() {

                    if($(document).height() > DesktopRemote.documentOriginalHeight)
                    {
                        $(DesktopRemote.desktop).animate({'height': (($(document).height() - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 10});
                    }
                    else
                    {
                        $(DesktopRemote.desktop).animate({'height': ((DesktopRemote.documentOriginalHeight - DesktopRemote.topBarHeight) - DesktopRemote.widgetBarHeight) - 20});
                    }

                }, 800);
            }

            // Unlock

            setTimeout(function() {

                $('#double_down').attr('id', 'double_up');
                $('#single_down').attr('id', 'single_up');
                $('.dektop_remote_object').removeClass('locked');
                $(DesktopRemote.remoteWrapper).animate({'margin-left': 0});

            }, 1000);
        }

    }

}
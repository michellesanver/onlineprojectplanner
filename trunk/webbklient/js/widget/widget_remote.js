
WidgetRemote = {

    widgetBar: '#widget_bar',
    widgetContainer: '#widget_bar .icon_container',
    widgetWrapper: '#widget_bar .icon_container .icon_wrapper',
    widgetWrapperWidth: null,
    icons: '#widget_bar .icon_container .icon_wrapper .icon',
    iconsWidth: 0,

    init:function() {

        $('#widget_bar .icon a[title]').tooltip({track: true,
        delay: 0,
        opacity: 0.5,
        fade: 0,
        top: 40,
        left: -100,
        showURL: false,
        extraClass: 'widget_bar_tooltip'});

        $(WidgetRemote.icons).each(function(index) {

            WidgetRemote.iconsWidth += $(this).width() + 30;

        });

        $(WidgetRemote.widgetContainer).css('width', WidgetRemote.iconsWidth);
        WidgetRemote.widgetWrapperWidth = $(WidgetRemote.widgetWrapper).width();

        $(WidgetRemote.widgetBar).mousemove(function(e) {

            var left = (e.pageX - $(WidgetRemote.widgetBar).offset().left) * (WidgetRemote.widgetWrapperWidth - $(WidgetRemote.widgetBar).width()) / $(WidgetRemote.widgetBar).width();

            $(WidgetRemote.widgetBar).scrollLeft(left);

        });

    }

}
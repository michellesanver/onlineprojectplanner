

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
              // run function to init and render widgetbar; send first data with
              // widgets and jquery path to where widgetbar should be rendered
              WidgetBar.init( <?php echo($widget_bar); ?>, '.icon_wrapper' ); // note; icon_wrapper is set in pre_content
              
              // init scroller for widgetbar (MUST BE CALLED AFTER WidgetBar.init!)
              WidgetRemote.init();
              
              // init minimize desktop etc
              DesktopRemote.init();
        });

    </script>

    <!-- Desktop Remote Start -->

    <div id="desktop_remote_wrapper">

        <div id="desktop_remote">
            <span id="single_up" class="dektop_remote_object" title="Show/Hide Widget Bar..."><span class="donotdisplay">&nbsp;</span></span>
            <span id="double_up"  class="dektop_remote_object" title="Show/Hide Top Bar and Widget Bar..."><span class="donotdisplay">&nbsp;</span></span>
        </div>

    </div>

    <!-- Desktop Remote End -->

</div>

</body>
</html>

/**
 * Handler for Widgetbar
 * @author Fredrik Johansson <tazzie76@gmail.com>
 * 
 * - render widgets in specified path
 * - add widgets
 * - remove widgets
 * - update widgets
 *
 * Note; The HTML-code for a widget-icon is placed
 * inside a private function names iconHTML in renderAllWidgets
 */

WidgetBar = {
    
    // id of most inner div for an icon
    widget_icon_id:"widget_icon_{WIDGET_ID}", // replace {WIDGET_ID} with projects_widgets_id
    
    // variables
    widgets: null,
    jquery_path: "",

    /**
     * init Widgetbar
     * 
     * @param array widget_data (array of objects with initial widgets)
     * @param string widgetbar_jquery_path (jquery path to where widgetbar should be placed)
     */
    init: function(widget_data, widgetbar_jquery_path) {
    
        // save widget data
        WidgetBar.widgets = widget_data;
        WidgetBar.jquery_path = widgetbar_jquery_path;
    
        // render all widgets
        WidgetBar.renderAllWidgets();
    },
    
    /**
     * Render all widgets; clear old widget icons
     * @param bool onlyRender (if undefined then also open widgets, if false then only render)
     * @param bool closeProcessing (if undefined and true; then close any processing currently active)
     */ 
    renderAllWidgets:function(onlyRender, closeProcessing) {
        
        // clear old widgetbar 
        $(WidgetBar.jquery_path).html(' ');
        
        // loop thru all widgets
        var widget_length = WidgetBar.widgets.length;
        for(var n=0; n<widget_length; n++)
        {
            
            // check so widget data exist (can be null if deleted)
            if ( WidgetBar.widgets[n] != undefined && WidgetBar.widgets[n] != null)
            {
                var widget = WidgetBar.widgets[n];
                var html = iconHTML();
                var func = openFunction();
                
                // insert values for function
                func = func.replace('{WIDGET_ID}', widget.project_widgets_id);
                func = func.replace('{WIDGET_STARTFUNCTION}', widget.widget_startfunction);
                func = func.replace('{WIDGET_OBJECT_NAME}', widget.widget_object_name);
                func = func.replace('{PROJECTS_WIDGETS_ID}', widget.project_widgets_id);
               
                // create a new object for params to last_position and insert
                var last_position = "";
                if (widget.last_position != undefined && widget.last_position.height != undefined && widget.last_position.width != undefined && widget.last_position.last_x != undefined && widget.last_position.last_y != undefined && widget.last_position.is_maximized != undefined )
                {
                
                    last_position = "{"+
                                        "'height': " + widget.last_position.height + ","+
                                        "'width': " + widget.last_position.width + ","+
                                        "'last_x': " + widget.last_position.last_x + ","+
                                        "'last_y': " + widget.last_position.last_y + ","+
                                        "'is_maximized': " + widget.last_position.is_maximized +
                                    " }";
                } else {
                    
                    // no last_position or missing values
                    last_position = "{}";
                }
                func = func.replace('{LAST_POSITION}', last_position);
                
                // insert values for icon and link
                html = html.replace('{WIDGET_ID}', widget.project_widgets_id);
                html = html.replace('{RUN_FUNCTION}', func);
                html = html.replace('{LINK_TITLE}', widget.widget_about);
                html = html.replace('{ICON_IMAGE}', widget.icon);
                html = html.replace('{LINK_NAME}', widget.widget_instance_name);
                
                // any state? and is state open? (used when moving a widget)'
                var dim_icon = false;
                if (widget.state != undefined && widget.state == "open") {
                    // set state for icon in html
                    html = html.replace('state=""', 'state="open"');
                    dim_icon = true;
                }
                
                // add html to document
                $(WidgetBar.jquery_path).append( html+"\n" );
                
                // dim icon?
                if ( dim_icon === true ) {
                    // use same dim effect as when clicked (see object/function Desktop.open_widget)
                    var widgetIconId = WidgetBar.widget_icon_id;
                    widgetIconId = widgetIconId.replace('{WIDGET_ID}', widget.project_widgets_id);
                    $('#'+widgetIconId).css({ 'opacity':'0.2', '-ms-filter':'"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"', 'filter':'alpha(opacity=20)' });
                }
                
                // render but not open?
                if ( onlyRender == undefined || ( onlyRender != undefined && onlyRender === false) ) {
                    
                    // check open widget
                    if (widget.last_position != undefined && widget.last_position.is_open != undefined && widget.last_position.is_open === true ) {
                        // yes, simulate click
                        WidgetBar.doIconClick(widget.project_widgets_id);
                    }
                }
            }
        }
        
        // close any processing-dialog? (used when moving widgets)
        if ( closeProcessing != undefined && closeProcessing === true) {
            $.jprocessing( "close" );
        }
        
        // exit function gracefully
        return;
    
        // -------------------------
        // closure to create private constants
        
        // this function returns the html for a widget
        function iconHTML() {
            
            /* triggers to replace:
             *
             * {WIDGET_ID}  - projects_widgets_id
             * {RUN_FUNCTION} - javascript-function to run on click
             * {LINK_TITLE} - attribute "title" in a href-link
             * {ICON_IMAGE} - full path to icon
             * {LINK_NAME} - name of widget (placed below icon)
             */
            
            return '<div class="icon" id="' + WidgetBar.widget_icon_id + '" state="">'+
                   '<a href="javascript:void(0);" onclick="{RUN_FUNCTION}" title="{LINK_TITLE}">'+
                   '<img src="{ICON_IMAGE}" alt="" /></a>'+
                   '<p>{LINK_NAME}</p>'+
                   '</div>';          
        }
        
        // this function returns the actual code that is executed on click
        function openFunction() {
            
            /* triggers to replace:
             *
             * {WIDGET_STARTFUNCTION} - startfunction in widget
             * {WIDGET_ID}  - projects_widgets_id
             * {WIDGET_OBJECT_NAME} - name of widget class
             * {PROJECTS_WIDGETS_ID} - projects_widgets_id
             * {LAST_POSITION} - object with data for last position
             */
            
            return "Desktop.open_widget("+
                     " '{WIDGET_STARTFUNCTION}',"+
                     " '"+ WidgetBar.widget_icon_id +"',"+
                     " '{WIDGET_OBJECT_NAME}',"+
                     " {PROJECTS_WIDGETS_ID},"+
                     " {LAST_POSITION}"+
                   "); return false;";
        }
    },
    
    /**
     * simulate a click for a widget icon
     * - will check attribute state and open if empty
     * 
     * @param int instance_id (also called project_widgets_id)
     */
    doIconClick: function(instance_id) {
    
        var div_id = WidgetBar.widget_icon_id;
        div_id = div_id.replace('{WIDGET_ID}', instance_id);
        
        var widget_div = $('#' + div_id);
        if ( widget_div.attr('state') == "" ) {
            var link = widget_div.find('a');
            link.click();
        }
    },
    
    /**
     * Change the name of a widget in the widgetbar
     * @param int instance_id (also called project_widgets_id)
     * @param string new_name
     */
    updateWidgetName: function(instance_id, new_name) {
        
        // loop through all widgets
        var widget_length = WidgetBar.widgets.length;
        for(var n=0; n<widget_length; n++) {
           
           if ( WidgetBar.widgets[n] != null) {
                var widget = WidgetBar.widgets[n];
            
                // does id match?
                if ( widget.project_widgets_id == instance_id  )
                {
                    // set new name and exit loop
                    WidgetBar.widgets[n].widget_instance_name = new_name;
                    break;
                }
           }
            
        }
        
        // render all widgets
        WidgetBar.renderAllWidgets();
    },
    
    /**
     * Delete a widget icon in the widgetbar
     * @param int instance_id (also called project_widgets_id)
     */
    deleteWidgetIcon: function(instance_id) {
        
        // loop through all widgets
        var widget_length = WidgetBar.widgets.length;
        for(var n=0; n<widget_length; n++) {
           
           if ( WidgetBar.widgets[n] != null) {
                var widget = WidgetBar.widgets[n];
            
                // does id match?
                if ( widget.project_widgets_id == instance_id  )
                {
                    // set as null
                    WidgetBar.widgets[n] = null;
                    break;
                }
           }     
        }
        
        // render all widgets
        WidgetBar.renderAllWidgets();
        
        // recalculate scroller
        WidgetRemote.update('remove');
    },
    
    /**
     * Update positions in the widgetbar
     * @param array new_positions
     */
    sortWidgets: function(new_positions) {
        
        // sort new data
        WidgetBar.sortNewWidgetData(new_positions);
        
        // render all widgets
        WidgetBar.renderAllWidgets(true, true);  // first parameter is onlyRender; do not open widgets again.
                                                 // second is close processing (used in widget_handler)
    },
    
    sortNewWidgetData: function(new_positions) {
      
        var new_widget_data = new Array();
        
        var widget_length = WidgetBar.widgets.length;
        var new_positions_length = new_positions.length;
        
        for (var i=0; i<new_positions_length; i++)
        {
        
            var current_widget_id = parseInt(new_positions[i]);
        
            // find widget in old data
            for(var n=0; n<widget_length; n++) { 
                
                if ( WidgetBar.widgets[n] != null) {
                    
                    var widget = WidgetBar.widgets[n];
                    if ( widget.project_widgets_id  == current_widget_id )
                    {
                        // get current state
                        var div_id = WidgetBar.widget_icon_id;
                        div_id = div_id.replace('{WIDGET_ID}', widget.project_widgets_id);
                        var widget_div = $('#'+div_id);
                        
                        if ( widget_div.attr("state") == "open" ) {
                            WidgetBar.widgets[n].state = "open";
                        } else {
                            WidgetBar.widgets[n].state = "";
                        }
                        
                        // save data
                        new_widget_data.push(WidgetBar.widgets[n]);
                        break;
                    }
                    
                }
            }
            
        }
        
        // save new array
        WidgetBar.widgets = new_widget_data; 
    },
    
    addWidget: function(json, order) {
        
        // convert to json?
        if ( typeof json != 'object') {
            json = $.parseJSON(json);
        }
        
        // add new data
        WidgetBar.widgets.push(json);
        
        // re-sort to correct position
        WidgetBar.sortNewWidgetData(order);
        
        // render all widgets
        WidgetBar.renderAllWidgets(true);
        
        // recalculate scroller
        WidgetRemote.update('add');
    }
    
    
};
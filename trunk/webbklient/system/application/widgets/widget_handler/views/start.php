
<script type="text/javascript">

$(document).ready(function(){

    $("#your_widgets").sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function () {  
           var order = $('#your_widgets').sortable('serialize');
            $.post("<?php echo($widget_url . 'widgets_handler/sort'); ?>", order);
        }
    }); 

});

</script>

<div id="widget_handler_content_wrapper">
	<div id="available_widgets_box">
	    <h1>Available widgets</h1>
	    <p>Click on a widget to add it to your project.</p>
	    <ul>
	        <?php foreach($allWidgets as $widget): ?>
	            <?php $id = $widget['id']; ?>
	            <a href="#" onclick="widgethandler.loadURL('/widgets_handler/<?php echo($id); ?>');">
	                <li>
	                    <img height="50px" src="<?php echo($widget['icon']); ?>" /><br />
	                    <?php echo($widget['icon_title']); ?><br />
	                </li>
	            </a>
	        <?php endforeach; ?>
	    </ul>
	</div>
	
	<div id="widgets-handler-dialog-confirm" title="Delete?" style="display:none;">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete this widget?</p>
	</div>
	
	<div id="widgets-handler-dialog-new-name" title="Rename widget" style="display:none;">
	    <p>Name of instance:<br /><input id="widget_new_name" type="text" size="30" maxlength="30"/></p>
	    <p id="widget_new_name_error" style="display:none;"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><strong>Only letters, spaces, numbers, ( and ) is allowed.</strong></p>
	    <p id="widget_new_name_error2" style="display:none;"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><strong>Maximum length is 30 characters.</strong></p>
	    <p id="widget_new_name_error3" style="display:none;"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><strong>Name is required.</strong></p>
	</div>
	
	<div id="widgets-handler-dialog-processing" title="Please wait" style="display:none;">
	    <p>Please wait while handling request...</p>
	</div>
	
	<div id="widgets-handler-processing-message" title="Message" style="display:none;"><p></p></div>
	
	<div id="your_widgets_box">
	    <h1>Your widgets</h1>
	    <p>Drag widgets around to sort them.</p>
	    <ul id="your_widgets">
	        <?php foreach($projectWidgets as $id => $widget): ?>
	            <li class="widget_handler_content" id="widgetslist_<?php echo($id); ?>">
	                
	                <span class="widget_handler_instance_name">
	                <img height="40px" src="<?php echo($widget['icon']); ?>" />
	                <?php echo($widget['instance_name']); ?></span>
	                
	                <?php if($widget['default'] == false): ?>
	                    <form method="post" action="" onsubmit="return false" class="actionform<?php echo($id); ?>">
	    
	                        <input type="hidden" value="<?php echo($id); ?>" name="widgetid" />
	
	                        <input class="widgets_handler_formbutton" type="button" value="Delete" onclick="widgethandler.deleteWidget('actionform<?php echo($id); ?>', '/widgets_handler', 'widgets-handler-dialog-confirm');" />
	                        <input class="widgets_handler_formbutton" type="button" value="Rename" onclick="widgethandler.renameWidget('actionform<?php echo($id); ?>', '/widgets_handler/rename', 'widgets-handler-processing-message', 'widgets-handler-dialog-processing', 'widgets-handler-dialog-new-name', 'widget_new_name', 'widget_new_name_error', 'widget_new_name_error2', 'widget_new_name_error3', '<?php echo addslashes($widget['instance_name']); ?>');" />
	                    </form>
	                <?php endif; ?>
	            </li>
	        <?php endforeach; ?>
	    </ul>
	</div>
</div>
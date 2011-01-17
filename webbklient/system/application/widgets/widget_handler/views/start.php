
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

<div id="your_widgets_box">
    <h1>Your widgets</h1>
    <p>Drag widgets around to sort them.</p>
    <ul id="your_widgets">
        <?php foreach($projectWidgets as $id => $widget): ?>
            <li id="widgetslist_<?php echo($id); ?>">
                <img height="50px" src="<?php echo($widget['icon']); ?>" /><br />
                <?php echo($widget['name']); ?><br />
                
                <?php if($widget['default'] == false): ?>
                    <form method="post" action="" onsubmit="return false" class="deleteform<?php echo($id); ?>">
    
                    <input type="hidden" value="<?php echo($id); ?>" name="deleteid" />
                        <input type="button" value="Delete" onclick="widgethandler.deleteWidget('deleteform<?php echo($id); ?>', '/widgets_handler');" />
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
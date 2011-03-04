<?php if (isset($addid)): /* create a div with the added widget id */ ?>
	<div id="new_widget_added" style="display:none;"><?php echo $addid; ?></div>
	<script type="text/javascript">widgethandler_new_widget_json = <?php echo $new_widget_json; ?>;</script>
<?php endif; ?>

<?php if (isset($error_message) && empty($error_message)==false): ?>
	<script type="text/javascript">$.jprocessing( "close" ); Desktop.show_errormessage('<?php echo $error_message; ?>');</script>
<?php endif; ?>

<!-- LOOK OUT! If you change any ID's or classes in this file, also look for them in the corresponding widgethandler.js -->
<div id="widget_handler_content_wrapper">
	<div id="available_widgets_box">
	    <h1>Available widgets</h1>
	    <p>Click on a widget to add it to your project.</p>
	    <ul>
	        <?php foreach($allWidgets as $widget): ?>
	            <?php $id = $widget['id']; ?>
	            <a href="#" id="addwidget" class="<?php echo($id);?>">
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
	    <p>Name of instance:<br />
	    	<input id="widget_new_name" type="text" size="30" maxlength="30"/>
	    </p>
	    
	    <p id="widget_new_name_error" style="display:none;">
	    	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
	    	<strong>Only letters, spaces, numbers, ( and ) is allowed.</strong>
	    </p>
	    
	    <p id="widget_new_name_error2" style="display:none;">
	    	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
	    	<strong>Maximum length is 30 characters.</strong>
	    </p>
	    
	    <p id="widget_new_name_error3" style="display:none;">
	    	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
	    	<strong>Name is required.</strong>
	    </p>
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
	                    <form 
	                    	id="widget_handler_form" 
	                    	method="post" 
	                    	action="" 
	                    	onsubmit="return false" 
	                    	class="actionform<?php echo($id); ?>"
	                    >
	    
	                        <input type="hidden" value="<?php echo($id); ?>" name="widgetid" />
	
	                        <input 
	                        	id="<?php echo($id); ?>"
	                        	class="widgets_handler_formbutton widgets_handler_delete_button" 
	                        	type="button" 
	                        	value="Delete" 
	                        />
	                        
	                        <input 
	                        	id="<?php echo($id . "_" . $widget["instance_name"]); ?>"
	                        	class="widgets_handler_formbutton widgets_handler_rename_button" 
	                        	type="button" 
	                        	value="Rename" 
	                        />
	                    </form>
	                <?php endif; ?>
	            </li>
	            
	        <?php endforeach; ?>
	    </ul>
	</div>
</div>
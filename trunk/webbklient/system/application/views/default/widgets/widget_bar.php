<?php foreach($widget_icons as $project_widget_id => $widget): ?>
	<div class="widget_listening">
		<img width="55px" src="<?php echo($widget['icon']); ?>" /><br/>
		<?php echo($widget['name']); ?>
	</div>
<?php endforeach; ?>
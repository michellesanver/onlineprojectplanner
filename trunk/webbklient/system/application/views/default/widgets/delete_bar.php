<?php foreach($delete_icons as $project_widget_id => $widget): ?>
	<div class="widget_listening">
		<a href="<?php echo site_url("project/update/{$projectID}/0/{$project_widget_id}"); ?>"><img src="<?php echo($widget['icon']); ?>" /></a><br/>
		<?php echo($widget['name']); ?>
	</div>
<?php endforeach; ?>
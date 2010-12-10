<h1 class="blackheader">Project preferences for "<?php echo (isset($title)) ? $title : ""; ?>"</h1>

<div id="contentboxwrapper">
	<div id="leftboxwide">
        <?php
            if(isset($status)) {
                echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
            }
        ?>

        <?php if(isset($title)) { ?>
        	<h2>Update project</h2>
            <form action="<?php echo site_url('project/update/'.$projectID.''); ?>" method="POST">
            	<input type="hidden" name="projectID" value="<?php echo (isset($projectID)) ? $projectID : ""; ?>" />
				<label for="description">Description: </label>
				<textarea rows="2" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*<br/>
                <label>&nbsp;</label><input type="submit" value="Update" name="update_btn" />
            </form><br/>
            
            <h2>Delete project</h2>
            <p>Press the button below to delete this project, it can not be undone!</p>
            <form action="<?php echo site_url('project/delete/'.$projectID.''); ?>" method="POST">
            	<input type="hidden" name="projectID" value="<?php echo (isset($projectID)) ? $projectID : ""; ?>" />
                <input type="submit" value="Delete" name="delete_btn" />
            </form>
        <?php } else { ?>
            <p><b>No project was found...</b></p>
        <?php } ?>
	</div>                               
	<div id="rightbox">
		<?php foreach($allwidgets as $widget): ?>
			<div class="widget_listening">
				<a href="<?php echo site_url("project/update/{$projectID}/{$widget['id']}"); ?>"><img src="<?php echo($widget['icon']); ?>" /></a><br/>
				<?php echo($widget['icon_title']); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
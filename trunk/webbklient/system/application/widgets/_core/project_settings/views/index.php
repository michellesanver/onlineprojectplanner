<div id="projectsettings_wrapper">
	<?php if(isset($Project_id)) { ?>
		<p>
			<h2>Project settings</h2>
			<ul>
				<li>To change the description of the project type the new description in the box below and click "Update".</li>
				<li>To Delete the project click the "Delete" button below.</li>
			</ul>
		</p>
		<p>
			<form id="proj_desc_<?php echo $Project_id; ?>" onsubmit="return Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'save');">
				<textarea rows="5" cols="45" name="Description" class="required" id="Description"><?php echo $Description; ?></textarea><br />
				<input type="submit" value="Update" name="update_btn" />
			</form>
		</p>
		<p>
			<h2>Delete project</h2>
			<p>
				Press the button below to delete this project, it can not be undone!
				<p><input type="button" value="Delete this project" name="delete_btn" onclick="return Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'del');" /></p>
			</p>
		</p>
	<?php } else { ?>
		<p><b>No project was found...</b></p>
	<?php } ?>
</div>  
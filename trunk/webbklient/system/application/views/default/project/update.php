<?php
            if(isset($status)) {
                echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
            }
        ?>

<h1 class="contentheader">Project preferences for "<?php echo (isset($title)) ? $title : ""; ?>"</h1>

<div id="contentboxwrapper">
	<div id="leftbox">
     

        <?php if(isset($title)) { ?>
        	<h2>Widgetinstructions</h2>
        	<p>
        		<ul>
        			<li>To remove a widget click it in the widgetbar on the top.</li>
        			<li>To add a widget click on it in the widgetlist to the right.</li>
        		</ul>
        	</p>
            <form action="<?php echo site_url('project/update/'.$projectID.''); ?>" method="POST">
            	<input type="hidden" name="projectID" value="<?php echo (isset($projectID)) ? $projectID : ""; ?>" />
				
				<textarea rows="5" cols="50" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*<br/>
                <input type="submit" value="Update" name="update_btn" /><input type="submit" value="Cancel" name="cancel_btn" />
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
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<?php $counter = 0; ?>
		<?php foreach($allwidgets as $widget): ?>
			<?php
			if($counter == 0) {
				echo "<tr width=\"25%\">";
			}
			echo "<td>";
			?>
			<div class="widget_listening">
				<a href="<?php echo site_url("project/update/{$projectID}/{$widget['id']}"); ?>"><img width="64px"alt="<?php echo($widget['icon_title']); ?>" src="<?php echo($widget['icon']); ?>" /></a><br/>
				<?php echo($widget['icon_title']); ?>
			</div>
			<?php
			echo "</td>";
			$counter++;
			if($counter == 4) {
				$counter = 0;
				echo "</tr>";
			}
			?>
		<?php endforeach; ?>
	</table>
	</div>

        <br style="clear:both;" />
</div>
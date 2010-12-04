
<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><b>" . $status_message . "</b></div>";
    }
?>
                
<h1 class="blackheader">Projects</h1>
<div id="projectlisting">
	<a class="newprojectbox" href="<?php echo site_url('project/register'); ?>">
		<p class="projectplus">&nbsp;</p>
		<b class="new_project_title">New project</b>
	</a>
        <a class="acceptinvitationbox" href="<?php echo site_url('project/accept'); ?>">
		<p class="projectplus">&nbsp;</p>
		<b class="accept_project_title">Accept invitation</b>
	</a>
<?php foreach($projects as $project): ?>
	<a class="projectbox" href="<?php echo site_url('project/'.$project['Project_id']); ?>">
		<b class="project_title"><?php echo($project['Title']);?></b><br/>
		<?php echo($project['Description']);?>
	</a>
<?php endforeach; ?>
</div>
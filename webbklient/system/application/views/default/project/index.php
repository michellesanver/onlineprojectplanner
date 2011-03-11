
<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><h3>" . $status_message . "</h3></div>";
    }
?>
                
<h1 class="contentheader">Projects</h1>
<div id="projectlisting">
	<a class="newprojectbox" href="<?php echo site_url('project/register'); ?>">
		<span class="projectplus">&nbsp;</span>
		<span class="new_project_title">New project</span>
	</a>
        <a class="acceptinvitationbox" href="<?php echo site_url('project/accept'); ?>">
		<span class="projectplus">&nbsp;</span>
		<span class="accept_project_title">Accept invitation</span>
	</a>
<?php foreach($projects as $project): ?>
	<a class="projectbox" href="<?php echo site_url('project/'.$project['Project_id']); ?>" title="Description: <?php echo($project['Description']);?>">
            <span class="project_title"><?php echo($project['Title']);?></span>
	</a>
<?php endforeach; ?>


        <br style="clear:left;" />
</div>
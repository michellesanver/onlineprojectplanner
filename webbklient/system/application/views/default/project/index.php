<h1 class="blackheader">Projects</h1>
<div id="projectlisting">
	<a class="newprojectbox" href="<?php echo site_url('project/register'); ?>">
		<img class="projectplus" src="<?php echo($base_url);?>images/backgrounds/plus.png"/>
		<b class="new_project_title">New project</b>
	</a>
<?php foreach($projects as $project): ?>
	<a class="projectbox" href="<?php echo site_url('project/'.$project['Project_id']); ?>">
		<b class="project_title"><?php echo($project['Title']);?></b><br/>
		<?php echo($project['Description']);?>
	</a>
<?php endforeach; ?>
</div>
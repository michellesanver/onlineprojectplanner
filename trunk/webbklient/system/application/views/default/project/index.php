<h1>Project listing</h1>
<p>Lists all of your projects</p>
	<div class="newprojectbox">
		<h2>New project</h2>
		<p><a href="<?php echo site_url('project_controller/register'); ?>">Create a new project</a></p>
	</div>
<?php foreach($projects as $project): ?>
	<div class="projectbox">
		<h2><?php echo($project['Title']);?></h2>
		<p><?php echo($project['Description']);?></p>
	</div>
<?php endforeach; ?>
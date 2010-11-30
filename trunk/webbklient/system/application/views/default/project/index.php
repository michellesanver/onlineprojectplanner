<h1 class="blackheader">Projects</h1>
<div id="projectlisting">
	<div class="newprojectbox">
		<h2 class="project_title">New project</h2>
		<p class="project_description"><a href="<?php echo site_url('project_controller/register'); ?>">Create a new project</a></p>
	</div>
<?php foreach($projects as $project): ?>
	<div class="projectbox">
		<h2 class="project_title"><?php echo($project['Title']);?></h2>
		<p class="project_description"><?php echo($project['Description']);?></p>
	</div>
<?php endforeach; ?>
</div>
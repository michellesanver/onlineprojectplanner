
<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>
<h1 class="blackheader">Register new project</h1>
<div id="contentboxwrapper">
	<div id="leftboxwide">
        <form action="<?php echo site_url('project/register'); ?>" method="POST">
                <label for="title">Title: </label><input type="text" name="title" value="<?php echo (isset($title)) ? $title : ""; ?>" />*<br/>
                <label for="description">Description: </label><textarea rows="4" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*<br/>
                <label>&nbsp;</label><input type="submit" value="Register" name="register_btn" /><br/>
        </form>
    </div>
    <div id="rightbox">
		<h2>What do I get?</h2>
		<ul>
			<li>You get this</li>
			<li>You get that</li>
			<li>It's free!</li>
		</ul>
	</div>
</div>


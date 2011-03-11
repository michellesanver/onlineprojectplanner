
<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>
<h1 class="contentheader">Register new project</h1>
<div id="contentboxwrapper">
	<div id="leftbox">
        <form action="<?php echo site_url('project/register'); ?>" method="POST">
                <div class="inputwrapper inputwrapper410"><label for="title">Title: *</label><input type="text" name="title" value="<?php echo (isset($title)) ? $title : ""; ?>" /><br style="clear:both;" /></div>
                <div class="inputwrapper inputwrapper410"><label for="description">Description: *</label><textarea rows="4" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea><br style="clear:both;" /></div>
                <div class="inputwrapper inputwrapper410"><label>&nbsp;</label><input type="submit" value="Register" name="register_btn" /><br style="clear:both;" /></div>
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

        <br style="clear:both;" />

</div>


<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>

<h1 class="contentheader">Accept project invitation</h1>
<div id="contentboxwrapper">
	<div id="leftbox">
		<form action="<?php echo site_url('project/accept'); ?>" method="POST">
			<div class="inputwrapper inputwrapper410"><label for="code">Invite Code: *</label><input type="text" name="code" value="<?php echo (isset($code)) ? $code : ""; ?>" /><br style="clear:both;" /></div>
			<div class="inputwrapper inputwrapper410"><label>&nbsp;</label><input type="submit" value="Accept" name="accept_btn" /><br style="clear:both;" /></div>
		</form>

	</div>
	<div id="rightbox">
		<h2>What is this?</h2>
		<p>If you have recieved an invitecode from someone you can type it in here to join their project.</p>
		<p>Your invitecode should be in your mail.</p>
	</div>

        <br style="clear:both;" />
</div>
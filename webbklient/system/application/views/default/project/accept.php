<?php
    if(isset($status)) {
            echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>

<h1 class="contentheader">Accept project invitation</h1>
<div id="contentboxwrapper">
	<div id="leftbox">
		<form action="<?php echo site_url('project/accept'); ?>" method="POST">
			<label for="code">Invite Code: </label><input type="text" name="code" value="<?php echo (isset($code)) ? $code : ""; ?>" />*<br/>
			<label>&nbsp;</label><input type="submit" value="Accept" name="accept_btn" /><br/>
		</form>

	</div>
	<div id="rightbox">
		<h2>What is this?</h2>
		<p>If you have recieved an invitecode from someone you can type it in here to join their project.</p>
		<p>Your invitecode should be in your mail.</p>
	</div>

        <br style="clear:both;" />
</div>

<div id="login_box">
	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h5>Logga in</h5>
	<form action="<?php echo site_url('user_controller/login'); ?>" method="POST">
		<p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*</p>
		<p><label for="password">Password: </label><input type="password" name="password" />*</p>
		<p><input type="submit" value="Sign in" name="login_btn" /></p>
	</form>
</div>










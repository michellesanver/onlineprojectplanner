

	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1>Login</h1>
	<form action="<?php echo site_url('account/login'); ?>" method="POST">
		<p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*</p>
		<p><label for="password">Password: </label><input type="password" name="password" />*</p>
		<p><input type="submit" value="Sign in" name="login_btn" /></p>
		<p><a href="<?php echo site_url('account/register'); ?>">Click here to register</a></p>
		<p><a href="<?php echo site_url('account/resetpassword'); ?>">Forgot password?</a></p>
	</form>


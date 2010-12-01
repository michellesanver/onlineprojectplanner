
<div id="login_box">
	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1>Login</h1>
	<form action="<?php echo site_url('user_controller/login'); ?>" method="POST">
		<p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*</p>
		<p><label for="password">Password: </label><input type="password" name="password" />*</p>
		<p><input type="submit" value="Sign in" name="login_btn" /></p>
		<p><a href="<?php echo site_url('user_controller/register'); ?>">Click here to register</a></p>
		<p><a href="<?php echo site_url('user_controller/resetpassword'); ?>">Forgot password?</a></p>
	</form>
</div>










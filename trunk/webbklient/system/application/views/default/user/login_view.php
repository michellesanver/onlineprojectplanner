<div id="loginbackground">

	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1>Login</h1>
	<form id="loginform" action="<?php echo site_url('account/login'); ?>" method="POST">
		<label for="username">Username: </label> <input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*<br/>
		<label for="password">Password: </label> <input type="password" name="password" />*<br/>
		<label>&nbsp; </label> <input type="submit" class="button" value="Sign in" name="login_btn" /><br/>
		<p class="small_links"><label>&nbsp; </label><a href="<?php echo site_url('account/register'); ?>">Click here to register</a> | <a href="<?php echo site_url('account/resetpassword'); ?>">Forgot password?</a></p>
	</form>

</div>
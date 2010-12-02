	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1 class="blackheader">Authenticate</h1>
	
	<form id="leftbox" action="<?php echo site_url('account/login'); ?>" method="POST">
		<label for="username">Username: </label> <input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*<br/>
		<label for="password">Password: </label> <input type="password" name="password" />*<br/>
		
		<label>&nbsp; </label><input type="submit" class="button" value="Sign in" name="login_btn" /><br/>
		<p class="forgotpassword"><a href="<?php echo site_url('account/resetpassword'); ?>">Forgot password?</a></p>
	</form>
	
	<div id="rightbox">
		<h2>Don't have an account? Register!</h2>
		<ul>
			<li>You can create your own projects.</li>
			<li>You can invite other members.</li>
			<li>Collaborate like never before</li>
			<li>It's free!</li>
		</ul>
		<p><a class="register" href="<?php echo site_url('account/register'); ?>">Click here to register</a></p>
	</div>
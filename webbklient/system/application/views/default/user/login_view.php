	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>

	<div id="contentboxwrapper">
		<div id="centerbox" class="authenticate">
                    <h2>Login</h2>
                    <form action="<?php echo site_url('account/login'); ?>" method="POST">
                        <div class="inputwrapper inputwrapper400"><label for="username">Username *</label> <input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper400"><label for="password">Password *</label> <input type="password" name="password" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper400"><input type="submit" class="button" value="Sign in" name="login_btn" /><br style="clear:both;" /></div>
                        <p><a href="<?php echo site_url('account/resetpassword'); ?>">Forgot your password?</a></p>
                    </form>
		</div>
	</div>

	
		<div id="register_box">
			<?php
				if(isset($status)) {
					echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
				}
			?>
			<form action="<?php echo site_url('user_controller/register'); ?>" method="POST">
				<p><label for="first_name">Firstname: </label><input type="text" name="first_name" value="<?php echo (isset($first_name)) ? $first_name : ""; ?>" />*</p>
				<p><label for="last_name">Lastname: </label><input type="text" name="last_name" value="<?php echo (isset($last_name)) ? $last_name : ""; ?>" />*</p>
				<p><label for="email">Email: </label><input type="text" name="email" value="<?php echo (isset($email)) ? $email : ""; ?>" />*</p>
				<p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" />*</p>
				<p><label for="password">Password: </label><input type="password" name="password" />*</p>
				<p><label for="password2">Repeat Password: </label><input type="password" name="password2" />*</p>
				<p><label for="streetadress">Streetadress: </label><input type="text" name="streetadress" value="<?php echo (isset($streetadress)) ? $streetadress : ""; ?>" /></p>
				<p><label for="postalcode">Postalcode: </label><input type="text" name="postalcode" value="<?php echo (isset($postalcode)) ? $postalcode : ""; ?>" /></p>
				<p><label for="hometown">Hometown: </label><input type="text" name="hometown" value="<?php echo (isset($hometown)) ? $hometown : ""; ?>" /></p>
				<p><input type="submit" value="Register" name="register_btn" /></p>
			</form>
		</div>


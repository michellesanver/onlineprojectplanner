	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
    <h1 class="contentheader">Register</h1>
    <div id="contentboxwrapper">
	    <div id="leftbox">
			<form id="registerform" action="<?php echo site_url('account/register'); ?>" method="POST">
				<p><label for="firstname">Firstname: </label><input type="text" name="firstname" value="<?php echo (isset($firstname)) ? $firstname : ""; ?>" />*</p>
				<p><label for="lastname">Lastname: </label><input type="text" name="lastname" value="<?php echo (isset($lastname)) ? $lastname : ""; ?>" />*</p>
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
		<div id="rightbox">
			<h2>What do I get?</h2>
			<ul>
				<li>You get this</li>
				<li>You get that</li>
				<li>It's free!</li>
			</ul>
			<p><i>This site will best work in Safari, Chrome and Firefox. Internet explorer is not supported.</i></p>
		</div>
        
                <br style="clear:both;" />
	</div>



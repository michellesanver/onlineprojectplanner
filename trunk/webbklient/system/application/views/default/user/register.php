	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
    <h1 class="contentheader">Register</h1>
    <div id="contentboxwrapper">
	    <div id="leftbox">
			<form id="registerform" action="<?php echo site_url('account/register'); ?>" method="POST">
				<div class="inputwrapper inputwrapper410"><label for="firstname">Firstname: *</label><input type="text" name="firstname" value="<?php echo (isset($firstname)) ? $firstname : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="lastname">Lastname: *</label><input type="text" name="lastname" value="<?php echo (isset($lastname)) ? $lastname : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="email">Email: *</label><input type="text" name="email" value="<?php echo (isset($email)) ? $email : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="username">Username: *</label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="password">Password: *</label><input type="password" name="password" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="password2">Repeat Password: *</label><input type="password" name="password2" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="streetadress">Streetadress:</label><input type="text" name="streetadress" value="<?php echo (isset($streetadress)) ? $streetadress : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="postalcode">Postalcode:</label><input type="text" name="postalcode" value="<?php echo (isset($postalcode)) ? $postalcode : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><label for="hometown">Hometown:</label><input type="text" name="hometown" value="<?php echo (isset($hometown)) ? $hometown : ""; ?>" /><br style="clear:both;" /></div>
				<div class="inputwrapper inputwrapper410"><input type="submit" value="Register" name="register_btn" /><br style="clear:both;" /></div>
			</form>
		</div>
		<div id="rightbox">
			<h2 style="padding-top:20px;">What do I get?</h2>
			<ul>
                            <li>You get this</li>
                            <li>You get that</li>
                            <li>It's free!</li>
			</ul>
			<p>This site will best work in Safari, Chrome and Firefox. Internet explorer is not supported.</p>
		</div>
        
                <br style="clear:both;" />
	</div>



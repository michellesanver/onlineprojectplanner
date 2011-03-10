
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>
                
<h1 class="contentheader">Account information</h1>

<div id="contentboxwrapper">
	<div id="leftbox">
	
	        <h3>Edit your account</h3>
	
	        <form action="<?php echo site_url('account/edit/'); ?>" method="POST">
						<p><label for="old_password">Old password: </label><input type="password" name="old_password" /> *</p>
						<p><label>Email: </label><?php echo (isset($Email)) ? $Email : ""; ?></p>
						<p><label>Username: </label><?php echo (isset($Username)) ? $Username : ""; ?></p>
						<p><label for="firstname">Firstname: </label><input type="text" name="firstname" value="<?php echo (isset($Firstname)) ? $Firstname : ""; ?>" /> *</p>
						<p><label for="lastname">Lastname: </label><input type="text" name="lastname" value="<?php echo (isset($Lastname)) ? $Lastname : ""; ?>" /> *</p>
						<p><label for="streetadress">Streetadress: </label><input type="text" name="streetadress" value="<?php echo (isset($Streetadress)) ? $Streetadress : ""; ?>" /></p>
						<p><label for="postalcode">Postalcode: </label><input type="text" name="postalcode" value="<?php echo (isset($Postalcode)) ? $Postalcode : ""; ?>" /></p>
						<p><label for="hometown">Hometown: </label><input type="text" name="hometown" value="<?php echo (isset($Hometown)) ? $Hometown : ""; ?>" /></p>
						<p><input type="submit" value="Update" name="edit_info_btn" /></p>
	        </form>
	</div>
	    <div id="rightbox">
	        <h3>Change your password</h3>
					
	        <form action="<?php echo site_url('account/edit/'); ?>" method="POST">
						<p><label for="old_password">Old password: </label><input type="password" name="old_password" /> *</p>
						<p><label for="new_password">New password: </label><input type="password" name="new_password" /> *</p>
						<p><label for="new_again_password">New password again: </label><input type="password" name="new_again_password" /> *</p>
						<p><input type="submit" value="Update" name="edit_pass_btn" /></p>
	        </form>
	    </div>

            <br style="clear:both;" />

	</div>
</div>
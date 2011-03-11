
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><h3>" . $status_message . "</h3>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>
                
<h1 class="contentheader">Account information</h1>

<div id="contentboxwrapper">
	<div id="leftbox">
	
	        <h3>Edit your account</h3>
	
	        <form action="<?php echo site_url('account/edit/'); ?>" method="POST">
                        <div class="inputwrapper inputwrapper410"><label for="old_password">Old password: *</label><input type="password" name="old_password" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label>Email:</label><span><?php echo (isset($Email)) ? $Email : ""; ?></span><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label>Username:</label><span><?php echo (isset($Username)) ? $Username : ""; ?></span><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label for="firstname">Firstname: *</label><input type="text" name="firstname" value="<?php echo (isset($Firstname)) ? $Firstname : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label for="lastname">Lastname: *</label><input type="text" name="lastname" value="<?php echo (isset($Lastname)) ? $Lastname : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label for="streetadress">Streetadress:</label><input type="text" name="streetadress" value="<?php echo (isset($Streetadress)) ? $Streetadress : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label for="postalcode">Postalcode:</label><input type="text" name="postalcode" value="<?php echo (isset($Postalcode)) ? $Postalcode : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><label for="hometown">Hometown:</label><input type="text" name="hometown" value="<?php echo (isset($Hometown)) ? $Hometown : ""; ?>" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper410"><input type="submit" value="Update" name="edit_info_btn" /><br style="clear:both;" /></div>
	        </form>
	</div>
	    <div id="rightbox">
	        <h3>Change your password</h3>
					
	        <form action="<?php echo site_url('account/edit/'); ?>" method="POST">
                        <div class="inputwrapper inputwrapper510"><label for="old_password">Old password: *</label><input type="password" name="old_password" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper510"><label for="new_password">New password: *</label><input type="password" name="new_password" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper510"><label for="new_again_password">New password again: *</label><input type="password" name="new_again_password" /><br style="clear:both;" /></div>
                        <div class="inputwrapper inputwrapper510"><input type="submit" value="Update" name="edit_pass_btn" /><br style="clear:both;" /></div>
	        </form>
	    </div>

            <br style="clear:both;" />

	</div>
</div>
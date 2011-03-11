<?php
	if(isset($status)) {
	    echo "<div class='" . $status . "'><h3>" . $status_message . "</h3></div>";
	}
?>

<h1 class="contentheader">Reset password</h1>

<div id="contentboxwrapper">

	<div id="leftbox">
        <?php if (isset($hideForm) == false || $hideForm == false) { ?>
            <form action="<?php echo site_url('account/resetpassword'); ?>" method="POST">

                <div class="inputwrapper inputwrapper410"><label for="email">Email:</label><input type="text" name="email" value="<?php echo (isset($email)) ? $email : ""; ?>" /><br style="clear:both;" /></div>
                
                <p><strong>OR</strong></p>
                
                <div class="inputwrapper inputwrapper410"><label for="username">Username:</label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /><br style="clear:both;" /></div>
                
                <div class="inputwrapper inputwrapper410"><input type="submit" value="Reset" name="reset_btn" /><br style="clear:both;" /></div>
            </form>
        <?php } ?>
	</div>
	<div id="rightbox">
		<p>If you have forgotten your password there's no need to worry. There will be an email sent to you if you enter your username or email in the form to the left with instructions how to reset your password. :)</p>
		<p>If you get no email please check your spamfolder or contact administration for further support.</p>
	</div>

        <br style="clear:both;" />
</div>


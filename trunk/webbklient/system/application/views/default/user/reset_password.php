
    


        <?php
            if(isset($status)) {
                echo "<div class='" . $status . "'><b>" . $status_message . "</b></div>";
            }
        ?>
        <h1>Reset password</h1> 
        <?php if (isset($hideForm) == false || $hideForm == false) { ?>
            <form action="<?php echo site_url('account/resetpassword'); ?>" method="POST">

                <p><label for="email">Email: </label><input type="text" name="email" value="<?php echo (isset($email)) ? $email : ""; ?>" /></p>
                
                <p>OR</p>
                
                <p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /></p>
                
                <p><input type="submit" value="Reset" name="reset_btn" /></p>
            </form>
        <?php } ?>


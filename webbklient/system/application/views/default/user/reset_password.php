

        <h1>Reset password</h1>

        <div id="reset_pw_box">
            <?php
                if(isset($status)) {
                    echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                }
            ?>
            <form action="<?php echo site_url('user_controller/reset_password'); ?>" method="POST">

                <p><label for="email">Email: </label><input type="text" name="email" value="<?php echo (isset($email)) ? $email : ""; ?>" /></p>
                
                <p>OR</p>
                
                <p><label for="username">Username: </label><input type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /></p>
                
                <p><input type="submit" value="Reset" name="reset_btn" /></p>
            </form>
        </div>


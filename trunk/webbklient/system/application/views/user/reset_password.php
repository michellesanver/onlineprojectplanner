<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">

<head>
    <title>Reset password</title>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />  
</head>
<body>

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

</body>
</html>


</body>
</html>
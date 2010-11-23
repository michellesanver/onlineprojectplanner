<h1>Login</h1>
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><b>" . $status_message . "</b></div>";
    }
?>

<?php if (isset($hideForm) == false || $hideForm == false) { ?>
    <form action="<?php echo site_url('user_controller/login'); ?>" method="POST">

        <p><label for="username">Username</label>:<input id="username" type="text" name="username" value="<?php echo (isset($username)) ? $username : ""; ?>" /></p>
        
        <p><label for="password">Password</label>:<input id="password" type="text" name="password" value="<?php echo (isset($password)) ? $password : ""; ?>" /></p>
        
        <p><input type="submit" value="Login" name="submit" /></p>
    </form>
    
    <p>Don't have an account? <a href="<?php echo site_url('user_controller/register'); ?>">Register here</a></p>
<?php } ?>

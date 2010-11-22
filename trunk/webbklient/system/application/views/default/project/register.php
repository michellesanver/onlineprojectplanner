<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">

	<head>
    	<title>Register Project</title>
   		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />  
	</head>
	<body>
	
            <div id="register_box">
                <?php
                    if(isset($status)) {
                            echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                    }
                ?>
                <form action="<?php echo site_url('project_controller/register'); ?>" method="POST">
                        <p><label for="title">Title: </label><input type="text" name="title" value="<?php echo (isset($title)) ? $title : ""; ?>" />*</p>
                        <p><label for="description">Description: </label><textarea rows="2" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*</p>
                        <p><input type="submit" value="Register" name="register_btn" /></p>
                </form>
            </div>

</body>
</html>
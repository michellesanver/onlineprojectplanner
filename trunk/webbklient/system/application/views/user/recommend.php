<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">

	<head>
    	<title>Recomend</title>
   		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />  
	</head>
	<body>
	
		<div id="register_box">
			<?php
				if(isset($status)) {
					echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
				}
			?>
			<h2>Remmend to a friend!</h2>
			<form action="<?php echo site_url('user_controller/RecommendNewUser'); ?>" method="POST">
				<p><label for="recEmail">Email: </label><input type="text" name="recEmail" /></p>
				<p><input type="submit" name="recSubmit" value="Recommend!" /></p>
			</form>
		</div>

</body>
</html>
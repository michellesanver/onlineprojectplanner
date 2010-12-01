
	

			<?php
				if(isset($status)) {
					echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
				}
			?>
			<h1>Remmend to a friend!</h1>
			<form action="<?php echo site_url('account/recommendnewuser'); ?>" method="POST">
				<p><label for="recEmail">Email: </label><input type="text" name="recEmail" /></p>
				<p><input type="submit" name="recSubmit" value="Recommend!" /></p>
			</form>


	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1>Settings</h1>
		<form id="registerform" action="<?php echo site_url('account/register'); ?>" method="POST">
			<?php				
				for($i = 0; $i < count($settings); $i++) {
					$val = isset($settings[$i]['Value']) ? $settings[$i]['Value'] : "";
					echo "<p><label for=\"" . $i . "\">" . $settings[$i]['Name'] . " </label><input type=\"text\" name=\"" . $i . "\" value=\"" . $val . "\" />*</p>";
				}
			?>
			<p><input type="submit" value="Save" name="register_btn" /></p>
		</form>



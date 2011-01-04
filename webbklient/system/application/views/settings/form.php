	<?php
		if(isset($status)) {
			echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
		}
	?>
	<h1>Settings</h1>
			<?php
				if(is_array($settings)) {
					echo "<form id=\"" . $id . "_settings\" onsubmit=\"return Desktop.saveSettingsForm()\">";
					for($i = 0; $i < count($settings); $i++) {
						$val = isset($settings[$i]['Value']) ? $settings[$i]['Value'] : "";
						echo "<p><label for=\"" . $settings[$i]['Widget_settings_value_id'] . "\">" . $settings[$i]['Name'] . " </label><input type=\"text\" name=\"" . $settings[$i]['Widget_settings_value_id'] . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" />*</p>";
					}
					echo "<p><input type=\"submit\" value=\"Save\" /></p>";
					
					echo "</form>";
				} else {
					echo "<p>There are no settings for this widget</p>";
				}
			?>



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
						$wsvi = (!isset($settings[$i]['Widget_settings_value_id']) || $settings[$i]['Widget_settings_value_id'] == "") ? "n".$settings[$i]['Settings_id'] : (int)$settings[$i]['Widget_settings_value_id'];
						
						echo "<p><label for=\"" . $wsvi . "\">" . $settings[$i]['Name'] . " </label>";
						switch($settings[$i]['Type_id']) {
							case '1':
								echo "<input type=\"text\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" /> * <em>(Only number)</em>";
								break;
							case '2':
								echo "<input type=\"text\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" /> *";
								break;
							case '3':
								$checked = ($val == "true" || $val == "1" || $val == "checked") ? "checked=\"checked\"" : ""; 
								echo "<input type=\"checkbox\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" " . $checked . " /> *";
								break;
							case '4':
								echo "<input type=\"text\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" /> * <em>(Only number)</em>";
								break;
							case '5':
								echo "<input type=\"text\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" /> *";
								break;
							default:
								echo "<input type=\"text\" name=\"" . $wsvi . "\" class=\"" . $settings[$i]['CI_rule'] . "\" value=\"" . $val . "\" />";
								break;
						}
						echo "</p>";
					}
					echo "<p><input type=\"submit\" value=\"Save\" /></p>";
					
					echo "</form>";
				} else {
					echo "<p>There are no settings for this widget</p>";
				}
			?>



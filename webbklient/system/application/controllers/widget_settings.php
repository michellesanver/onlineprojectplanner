<?php

class widget_settings extends Controller {
	
	function Widget_settings()
	{
		parent::Controller();
		$this->load->model(array("settings_model", "Widgets_model"));
		$this->load->library("settings_provider");
	}
	
	/**
	* Prints the dynamic created formfield.
	* 
	* @param int $projectWidgetId
	*/
	function GetProjectWidgetSettings($projectWidgetId)
	{
		if(($projectWidgetId = (int)$projectWidgetId) == false) {
			echo "Input is not an int!";
		}
		
		$data = array();
		$data['id'] = $projectWidgetId;
		
		$widget_id = $this->Widgets_model->GetWidgetId($projectWidgetId);
		if($widget_id > 0) {
			$data['settings'] = $this->settings_model->GetProjectWidgetSettings($widget_id, $projectWidgetId);
			//var_dump($data['settings']);
			$this->load->view('settings/form', $data);
		} else {
			echo "Problems with the widget_id.";
		}
	}
	
	
	/**
	* catches the validated post data and transfer if to the settings_model
	*/
	function SaveProjectWidgetSettings()
	{
		$post = $_POST;
		$project_widget_id = array_pop($post);
		$valueCount = count($post); 
		
		/*
		* Preparing data
		*/
		$data = array();
		$keys = array_keys($post);
		for($i = 0; $i < $valueCount; $i++) {
			$key = $keys[$i];
			if($post[$key] != ""){
				$tmp = array();
				$tmp['Widget_settings_value_id'] = $key;
				$tmp['Value'] = (string)$post[$key];
				$data[] = $tmp;
			}
		}
			
		$res = true;
		foreach($data as $row){
			if(substr($row['Widget_settings_value_id'],0,1) == "n"){
				$s_id = substr($row['Widget_settings_value_id'],1);
				if($this->settings_model->insertSettingValue(array("Project_widgets_id" => $project_widget_id, "Settings_id" => $s_id, "Value" => $row['Value'])) == false) {
					echo "Error while inserting: " . $row['Widget_settings_value_id'] . " = " . $row['Value'] . "<br />";
					$res = false;
				}
			} else {
				if($this->settings_model->updateSettingValue($row) == false) {
					echo "Error while updating: " . $row['Widget_settings_value_id'] . " = " . $row['Value'] . "<br />";
					$res = false;
				}
			}
		}
		if($res)
			echo "true";
	}
	
	function libTester()
	{
		var_dump($this->settings_provider->getSettingValue("ajax_template", 1, 9));
	}
}

/* End of file widget_settings.php */
/* Location: ./system/application/controllers/widget_settings.php */
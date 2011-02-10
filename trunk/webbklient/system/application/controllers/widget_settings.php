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
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
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
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		$post = $_POST;
		$event = array_pop($post);
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
		
		
		$return = array(
			"status" => "error",
			"status_message" => "Error while updating: "
		);
		$status = null;
		for($i = 0; $i < count($data); $i++){
			if(substr($data[$i]['Widget_settings_value_id'],0,1) == "n"){
				$data[$i]['Widget_settings_value_id'] = substr($data[$i]['Widget_settings_value_id'],1);
				if($this->settings_model->insertSettingValue(array("Project_widgets_id" => $project_widget_id, "Settings_id" => $data[$i]['Widget_settings_value_id'], "Value" => $data[$i]['Value'])) == false) {
					$return["status_message"] .= $data[$i]['Widget_settings_value_id'] . " = " . $data[$i]['Value'] . "<br />";
					$status = false;
				} else {
					if($status === null || $status != false)
						$status = true;
				}
			} else {
				if($this->settings_model->updateSettingValue($data[$i]) == false) {
					$return["status_message"] .= $data[$i]['Widget_settings_value_id'] . " = " . $data[$i]['Value'] . "<br />";
					$status = false;
				} else {
					if($status === null || $status !== false)
						$status = true;
				}
			}
		}
		if($status){
			$eventData = array();
			if($event == "true"){
				foreach($data as $row){
					$n = $this->settings_model->getSettingName($row['Widget_settings_value_id']);
					$eventData[] = array(
						"Internal_id" => $n->Internal_id,
						"Name" => $n->Name,
						"Value" => $row['Value']
					);
				}
			}
			$return = array(
				"status" => "ok",
				"status_message" => "The settings has been saved!",
				"data" => $eventData
			);
		}
		echo json_encode($return);
	}
}

/* End of file widget_settings.php */
/* Location: ./system/application/controllers/widget_settings.php */
<?php

class Widget_settings extends Controller {
	
	function Widget_settings()
	{
		parent::Controller();
		$this->load->model(array("settings_model", "Widgets_model"));
		$this->load->library(array('validation'));
	}
	
	/**
	* Prints the dynamic created formfield.
	* 
	* @param int $projectWidgetId
	*/
	function GetProjectWidgetSettings($projectWidgetId)
	{
		$data = array();
		$data['id'] = $projectWidgetId;
		
		$widget_id = $this->Widgets_model->GetProjectWidgetId($projectWidgetId);
		$data['settings'] = $this->settings_model->GetProjectWidgetSettings($widget_id, $projectWidgetId);
		
		$this->load->view('settings/form', $data);
	}
	
	
	/**
	* catches the validated post data and transfer if to the settings_model
	*/
	function SaveProjectWidgetSettings()
	{
		$post = $_POST;
		$valueCount = count($post); 
		
		/*
		* Preparing data
		*/
		$data = array();
		$keys = array_keys($post);
		for($i = 0; $i < $valueCount; $i++) {
			$key = $keys[$i];
			$tmp = array();
			$tmp['Widget_settings_value_id'] = $key;
			$tmp['Value'] = $post[$key];
			$data[] = $tmp;
		}
		
		$res = "true";
		foreach($data as $row){
			if($this->settings_model->updateSettingValue($row) == false) {
				echo $row['Value'] . "<br />";
				$res = "false";
			}
		}
		
		echo $res;
	}
}

/* End of file widget_settings.php */
/* Location: ./system/application/controllers/widget_settings.php */
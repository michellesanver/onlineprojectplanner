<?php

class Widget_settings extends Controller {

	private $_table1 = "Widget_Settings";
	private $_table2 = "Widget_Settings_Type";
	private $_table3 = "Widget_Settings_Value";
	
	function Widget_settings()
	{
		parent::Controller();
		$this->load->model("settings_model");
	}
	
	function GetProjectWidgetSettings($projectWidgetId)
	{
		$data = array();
		$data['id'] = $projectWidgetId;
		$data['settings'] = $this->settings_model->GetProjectWidgetSettings($projectWidgetId);
		
		$this->load->view('settings/form', $data);
	}
}

/* End of file widget_settings.php */
/* Location: ./system/application/controllers/widget_settings.php */
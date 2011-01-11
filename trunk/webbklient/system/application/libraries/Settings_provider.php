<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about a widgets settings
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Settings_provider
{
	private $_CI = null; 
	
	function __construct()
	{
		// get CI instance
		$this->_CI = & get_instance();
		$this->_CI->load->model(array("settings_model", "Widgets_model"));
		
	}

	/**
	* Returns the value of an specific setting. The $Widget 
	* parameter can iether be the name och the id of the widget.
	* 
	* @param mixed $Widget
	* @param int $Internal_id
	* @param int $Project_widget_id
	* @return mixed
	*/
	function getSettingValue($Widget, $Internal_id, $Project_widget_id)
	{
		// test if the input is an id or name.
		if((int)$Widget)
			$Widget_id = (int)$Widget;
		else
			// Fetch the id
			$Widget_id = $this->_CI->Widgets_model->GetWidgetId($Widget);
		
		// Test if the input is an int
		if(($Internal_id = (int)$Internal_id) == false) { return false; }
		
		// Test if the input is an int
		if(($Project_widget_id = (int)$Project_widget_id) == false) { return false; }
		
		return $this->_CI->settings_model->getSettingValue($Widget_id, $Internal_id, $Project_widget_id);
	}
}
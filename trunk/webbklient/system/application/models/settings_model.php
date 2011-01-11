<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library Settings
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Settings_model extends Model 
{
	private $_table1 = "Widget_Settings";
	private $_table2 = "Widget_Settings_Type";
	private $_table3 = "Widget_Settings_Value";
	
	function Widget_settings()
	{
		parent::Model();	
	}
	
	/**
	* returns an array containing the settingens for a widget with the right values
	* 
	* @param int $widget_id
	* @param int $projectWidgetId
	* @return mixed
	*/
	function GetProjectWidgetSettings($widget_id, $projectWidgetId)
	{
		$t1 = $this->_table1;
		$t2 = $this->_table2;
		$t3 = $this->_table3;
		$this->db->select("$t1.Settings_id, $t1.Name, $t2.Type_id, $t2.CI_rule, $t3.Widget_settings_value_id, $t3.Value");
		$this->db->from($t1);
		$this->db->where('Widget_id', $widget_id);
		$this->db->join($t2, "$t1.Type_id = $t2.Type_id");
		$this->db->join($t3, "$t1.Settings_id = $t3.Settings_id", "left outer");
		$this->db->where("$t3.Project_widgets_id", $projectWidgetId);
		$this->db->or_where("$t3.Project_widgets_id", null);
		$this->db->order_by("$t1.Order ASC");
		$query = $this->db->get();
		
	 // any result?
	 if ($query && $query->num_rows() > 0)
	 {
			$result = $query->result_array();
			return $result;
	 }
	 else
	 {
			return false;
	 }
	}
	
	/**
	* Returns the value of an specific setting
	* 
	* @param int $Widget_id
	* @param int $Internal_id
	* @param int $Project_widget_id
	* @return mixed
	*/
	function getSettingValue($Widget_id, $Internal_id, $Project_widget_id)
	{
		$t1 = $this->_table1;
		$t3 = $this->_table3;
		$this->db->select("$t3.Value");
		$this->db->from($t1);
		$this->db->where('Widget_id', $Widget_id);
		$this->db->where('Internal_id', $Internal_id);
		$this->db->join($t3, "$t1.Settings_id = $t3.Settings_id");
		$this->db->where('Project_widgets_id', $Project_widget_id);
		
		$query = $this->db->get();
		$row = $query->row();
		
		if(empty($row)) {
			return false;
		} else {
			return $row->Value;
		}
	}
	
	/**
	* Updates a row in the value table.
	* 
	* @param array $update
	* @return bool
	*/
	function updateSettingValue($update)
	{
		$this->db->where('Widget_settings_value_id', $update['Widget_settings_value_id']);
		return $this->db->update($this->_table3, $update);
	}
	
	/**
	* Inserts a row in the value table.
	* 
	* @param array $insert
	* @return bool
	*/
	function insertSettingValue($insert)
	{
		return $this->db->insert($this->_table3, $insert);
	}
}
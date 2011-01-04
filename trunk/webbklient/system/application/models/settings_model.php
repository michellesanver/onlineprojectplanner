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
	
	function GetProjectWidgetSettings($projectWidgetId)
	{
		$t1 = $this->_table1;
		$t2 = $this->_table2;
		$t3 = $this->_table3;
		$this->db->select("$t3.Value, $t1.Name, $t2.CI_rule");
		$this->db->from($t3);
		$this->db->where('Project_widgets_id', $projectWidgetId);
		$this->db->join($t1, "$t3.Settings_id = $t1.Settings_id");
		$this->db->join($t2, "$t1.Type_id = $t2.Type_id");
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
}
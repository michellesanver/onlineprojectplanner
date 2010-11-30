<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the class Widgets
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Widgets_model extends Model  {
    
     private $_table = "Project_Widgets";
    
    /**
    * Get all widgets for a specific project
    * 
    * @param string $projectID
    * @param bool active (optional, default true)
    * @return mixed
    */
     function GetProjectWidgets($projectID, $active=true)
     {
         // prepare
         $active = ($active === true ? 1 : 0);
         
         // run query
         $query = $this->db->get_where($this->_table, array('Is_active' => $active, 'Project_id' => $projectID));
         
         // any result?
         if ($query && $query->num_rows() > 0)
            return $query->result();
         else
            return false;
     }
    
    
}
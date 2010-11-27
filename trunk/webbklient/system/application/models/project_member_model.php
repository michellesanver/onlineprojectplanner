<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table Project_Member
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Project_member_model extends Model
{
	
	private $_table = "Project_Member";
	
	/**
	* Function: insert
	* This will insert it as a new row in the database.
	* Insert parameter can be an array or a object of stdClass.
	* 
	* @param mixed $insert
	* @return bool
	*/

	function insert($insert)
	{
            return $this->db->insert($this->_table, $insert);
	}

}
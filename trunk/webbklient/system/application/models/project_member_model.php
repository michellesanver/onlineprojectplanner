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
    private $_table_role = "Project_Role";
    private $_table_user = "User";
    
    /**
    * Function: getByUserId
    * This function will return an array representing
    * the memberships of the $userID and also join with
    * project_role.
    *
    * @param int $userID
    * @return mixed
    */
	function getByUserId($userID)
	{
        $table1 = $this->_table;
        $table2 = $this->_table_role;
        
        $this->db->select("$table1.*, $table2.Role");
        $this->db->where(array('User_id' => $userID));
        $this->db->from($table1);
        $this->db->join($table2, "$table1.Project_role_id = $table2.Project_role_id");
		$query = $this->db->get();
        
		if($query && $query->num_rows() > 0 )
			return $query->result_array();
		else
			return null;
	}

        /**
    * Function: getByProjectId
    * This function will return an array representing
    * the memberships of the $projectID and also join with
    * project_role and user.
    *
    * @param int $projectID
    * @return mixed
    */
	function getByProjectId($projectID)
	{
        $table1 = $this->_table;
        $table2 = $this->_table_user;
        $table3 = $this->_table_role;

        $this->db->select("$table1.*, $table2.*, $table3.Role");
        $this->db->where(array('Project_id' => $projectID));
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->join($table3, "$table1.Project_role_id = $table3.Project_role_id");
		$query = $this->db->get();

		if($query && $query->num_rows() > 0 )
			return $query->result_array();
		else
			return null;
	}

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
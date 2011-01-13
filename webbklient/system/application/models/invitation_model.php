<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table User_activation
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Invitation_model extends Model
{
	
	private $_table = "Project_Invitation";
	
	/**
	* Function: getWithCode
	* This function will return an array that represents the
	* rows in the database. Returns the row if there is only
	*1 match, else false;
	* 
	* @param string $code
	* @return mixed
	*/
	function getWithCode($code)
	{
		$query = $this->db->get_where($this->_table, array('Code' => $code));
		$res = $query->result_array();
		if(count($res) == 1)
			return $res[0];
		else
			return null;
	}
	
	/**
	* Function: getByProjectid
	* This function will return an array that represents the
	* rows in the database. Returns the row if there is only
	*1 match, else false;
	* 
	* @param int $projectID
	* @return mixed
	*/
	function getByProjectId($projectID)
	{
		$query = $this->db->get_where($this->_table, array('Project_id' => $projectID));
		$res = $query->result_array();
		if(count($res) == 1)
			return $res[0];
		else
			return null;
	}
	
	/**
	* Function: getAll
	* This function will return an array of arrays
	* that represents the rows in the database. 
	* 
	* @return array
	*/
	function getAll()
	{
		$query = $this->db->get($this->_table);
		return $query->result_array();
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
		$this->db->insert($this->_table, $insert);
		return $this->db->insert_id();

	}
	
	/**
	* Function: update
	* This will update the row in the database.
	* 
	* @param array $update
	* @return bool
	*/
	function update($update)
	{
		$this->db->where('Project_invitation_id', $update['Project_invitation_id']);
		return $this->db->update($this->_table, $update);
	}
	
	/**
	* Function: delete
	* This will delete a row in the database that matches
	* the param.
	* 
	* @param int $projectInvitationID
	* @return bool
	*/
	function delete($projectInvitationID)
	{
		return $this->db->delete($this->_table, array('Project_invitation_id' => $projectInvitationID));
	}
}
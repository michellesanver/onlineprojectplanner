<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library Error_log
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Error_model extends Model 
{
	
	private $tableName = "Error_Log";
	
	/**
		* This function will return an array representing
		* the user of the $err_id
		* 
		* @param int $err_id
		* @return mixed
		*/
	function getById($err_id)
	{
		$query = $this->db->get_where($this->tableName, array('Error_id' => $err_id));
		$res = $query->result_array();
		if(count($res) == 1)
			return $res[0];
		else
			return null;
	}
	
	/**
	* This function will return an array of arrays
	* that represents the rows in the database. 
	* 
	* @return array
	*/
	function getAll()
	{
		$query = $this->db->get($this->tableName);
		return $query->result_array();
	}
	
	/**
	* Used to send the validated information to the database,
	* Insert parameter can be an array or a object of stdClass.
	* 
	* @param mixed $insert
	* @return int
	*/
	function insert($insert)
	{
		$this->db->insert($this->tableName, $insert);
		return $this->db->insert_id();
	}
	
	/**
	* Used to send the validated information to the Database,
	* 
	* @param array $insert
	* @return bool
	*/
	function update($update)
	{
		$this->db->where('Error_id', $update['Error_id']);
		return $this->db->update($this->tableName, $update);
	}
	
	/**
		* Used to send the validated information to the User_model,
		* which will update the row in the database.
		* 
		* @param int $userID
		* @return bool
		*/
	function delete($err_id)
	{
		return $this->db->delete($this->tableName, array('Error_id' => $err_id)); 
	}
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table User_activation
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Activation_model extends Model 
{
	
	private $tableName = "User_Activation";
	
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
		$query = $this->db->get_where($this->tableName, array('Code' => $code));
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
		$query = $this->db->get($this->tableName);
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
		return $this->db->insert($this->tableName, $insert);
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
		$this->db->where('Activation_id', $update['Activation_id']);
		return $this->db->update($this->tableName, $update);
	}
	
	/**
	* Function: delete
	* This will delete a row in the database that matches
	* the param.
	* 
	* @param int $ActivationID
	* @return bool
	*/
	function delete($ActivationID)
	{
		return $this->db->delete($this->tableName, array('Activation_id' => $ActivationID)); 
	}
}
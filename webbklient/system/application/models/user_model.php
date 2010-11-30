<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library User
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class User_model extends Model 
{
	
	private $tableName = "User";
	
    /**
    * Query if the user exists by email or username.
    * Returns false or the row from the database.
    * 
    * @param string $email
    * @param string $username
    * @return mixed
    */
    function QueryUser($email, $username)
    {
        // run query
        $this->db->where('Username', $username);     
        $this->db->or_where('Email', $email);     
        $this->db->limit(1);
        $query = $this->db->get($this->tableName);
       
        // any result?
        if ($query->num_rows() > 0)
            // return row
            return $query->row(0);
        else
            // user not found
            return false; 
	}
	
	/**
		* Function: getById
		* This function will return an array representing
		* the user of the $userID
		* 
		* @param int $userID
		* @return mixed
		*/
	function getById($userID)
	{
		$query = $this->db->get_where($this->tableName, array('User_id' => $userID));
		$res = $query->result_array();
		if(count($res) == 1)
			return $res[0];
		else
			return null;
	}
	
	/**
		* Function: getByUsername
		* This function will return an array representing
		* the user of the $username
		* 
		* @param string $username
		* @return mixed
		*/
	function getByUsername($username)
	{
		$query = $this->db->get_where($this->tableName, array('Username' => $username));
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
	* Function: update
	* Used to send the validated information to the Database,
	* 
	* @param array $insert
	* @return bool
	*/
	function update($update)
	{
		$this->db->where('User_id', $update['User_id']);
		return $this->db->update($this->tableName, $update);
	}
	
	/**
		* Function: update
		* Used to send the validated information to the User_model,
		* which will update the row in the database.
		* 
		* @param int $userID
		* @return bool
		*/
	function delete($userID)
	{
		return $this->db->delete($this->tableName, array('User_id' => $userID)); 
	}
	
	function Test()
	{
			$xhtml = "<H4>Insert test</H4><br />";
			/*$res = $this->insert_user("Ronald","Mcdonald", "ronald@gmail.com","ronald123","ronald","brandonstreet","90210", "new york");
			if($res == true)
				$xhtml .= "Ditt konto är nu skapat<br />";
			else
				$xhtml .= "Det gick inte att skapa ditt konto<br />";*/
			
			$xhtml .= "<H4>Select med id test</H4><br />";
			//plocka ut user med känt id
			$x = $this->select_user(4);
			$xhtml .= $x['Firstname'] . "<br />";
			$xhtml .= $x['Lastname'] . "<br />";
			$xhtml .= $x['Email'] . "<br />";
			$xhtml .= $x['Password'] . "<br />";
			$xhtml .= $x['Username'] . "<br />";
			$xhtml .= $x['Streetadress'] . "<br />";
			$xhtml .= $x['Postalcode'] . "<br />";
			$xhtml .= $x['Hometown'] . "<br />";
			
		
			$xhtml .= "<H4>Lista alla användare</H4><br />";
			//plocka ut alla users
			//$xhtml .= $this->select_all_users();
			
			$xhtml .= "<H4>Delete användare med id</H4><br />";
			/*$id = 5;
			$res = $this->delete_user($id);
			if($res == true)
				$xhtml .= "Användaren bortagen<br />";
			else
				$xhtml .= "Det gick inte att ta bort användaren<br />";*/
				
			$xhtml .= "<H4>Uppdatera användare med id</H4><br />";
			/*$id = 4;
			$res = $this->update_user($id,"Ronaaald","Mcccdonald", "ronald@gmail.com","ronald123","ronald","brandonstreet","90210", "new york");
			if($res == true)
				$xhtml .= "Ditt konto är nu updaterat<br />";
			else
				$xhtml .= "Det gick inte att updatera ditt konto<br />";*/
				
			return $xhtml;
	
	}
	
	/**
	 * This function checks against the database if the login is true or false.
	 * @return boolean 
	 */
	function checkLogin($username,$encryptedpassword)
	{
		$query = $this->db->get_where('User', array('Username' => $username,'Password' => $encryptedpassword));

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
	        {
				$pers = array('UserID' => $row->User_id, 'login_status' => "online");
				return $pers;
	        }
		}  
		return false;
	}
	
}
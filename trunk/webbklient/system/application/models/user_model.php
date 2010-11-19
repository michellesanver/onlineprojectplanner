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
    function query_user($email, $username)
    {
        // run query
        $this->db->where('User_name', $username);     
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
    * Save confirmation code for a user to
    * reset password
    * 
    * @param int $uid
    * @param string $code
    * @return bool
    */
    function save_confirmation_code($uid, $code)
    {
        $data = array(
            'Reset_code' => $code
        );
        
        $this->db->where('UserID', $uid);    
        
        $res = $this->db->update($this->tableName, $data);
        
        if ( $res == false )
        {
            return false;
        }
        return true;
    }
    
    /**
    * Checks if confirmation code is correct for user. Returns
    * false or if correct, the row for the user.
    * 
    * @param int $uid
    * @param int $code
    * @return mixed
    */
    function check_confirmation_code($uid, $code)
    {
        // run query
        $this->db->where('UserID', $uid);     
        $this->db->where('Reset_code', $code);     
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
    * Update a password for a user. Will return true or false.
    * 
    * @param int $uid
    * @param string $password
    * @param bool $clear_reset_code (optional, default false)
    * @return bool
    */
    function update_password($uid, $password, $clear_reset_code=false)
    {
        $data = array(
            'Password' => $password
        );
        
        if ($clear_reset_code) $data['Reset_code'] = 'NULL';
        
        $this->db->where('UserID', $uid);    
        
        $res = $this->db->update($this->tableName, $data);
        
        if ( $res == false )
        {
            return false;
        }
        return true; 
    }
    
    
    
	function select_user($userID)
	{
		$res = "";
		$query = $this->db->get_where($this->tableName, array('UserID' => $userID));
		foreach ($query->result() as $row)
		{
		    $res['First_name'] = $row->First_name;
			$res['Last_name'] = $row->Last_name;
			$res['Email'] = $row->Email;
			$res['Password'] = $row->Password;
			$res['User_name'] = $row->User_name;
			$res['Streetadress'] = $row->Streetadress;
			$res['Postalcode'] = $row->Postalcode;
			$res['Hometown'] = $row->Hometown;
		}
		return $res;
	}
	
	/**
	* Function: select_all_users
	* This function will return an array of arrays
	* that represents the rows in the database. 
	* 
	* @return array
	*/
	function select_all_users()
	{
		$query = $this->db->get($this->tableName);
		$ret = array();
		
		// Fetches the data from the rows
		foreach($query->result() as $row) {
			$ret[] = array(
			"UserID" => $row->UserID,
			"First_name" => $row->First_name,
			"Last_name" => $row->Last_name,
			"Email" => $row->Email,
			"Password" => $row->Password,
			"User_name" => $row->User_name,
			"Reset_code" => $row->Reset_code,
			"Streetadress" => $row->Streetadress,
			"Postalcode" => $row->Postalcode,
			"Hometown" => $row->Hometown
			);
		}
		return $ret;
	}
	
	/**
	* Function: insert_user
	* Used to send the validated information to the User_model,
	* which will insert it as a new row in the database.
	* Insert parameter can be an array or a object of stdClass.
	* 
	* @param mixed $insert
	* @return bool
	*/
	function insert_user($insert)
	{
		$this->db->insert($this->tableName, $insert);
		return $this->db->insert_id();
	}
	
	/**
	* Function: update_user
	* Used to send the validated information to the User_model,
	* which will update the row in the database.
	* 
	* @param array $insert
	* @return bool
	*/
	function update_user($update)
	{
		$this->db->where('UserID', $update['UserID']);
		return $this->db->update($this->tableName, $update);
	}
	
	
	function delete_user($userID)
	{
		$res = $this->db->delete($this->tableName, array('UserID' => $userID)); 
		if($res == false)
		{
			return false;
		}
		return true;
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
			$xhtml .= $x['First_name'] . "<br />";
			$xhtml .= $x['Last_name'] . "<br />";
			$xhtml .= $x['Email'] . "<br />";
			$xhtml .= $x['Password'] . "<br />";
			$xhtml .= $x['User_name'] . "<br />";
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
	
}
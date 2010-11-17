<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library User
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class User_model extends Model 
{
	function select_user($userID)
	{
		$res = "";
		$query = $this->db->get_where('User', array('UserID' => $userID));
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
	
	function select_all_users()
	{
		$res = "";
		$query = $this->db->get('User');
		foreach ($query->result() as $row)
		{
		    $res .= $row->First_name . "<br />";
		}
		return $res;
	}
	
	function insert_user($fname, $lname, $email, $password, $username, $street, $postalcode, $hometown)
	{
		//md5 lösenordet innan det sparas
		$new_member_insert_data = array(
			'First_name' => $fname,
			'Last_name' => $lname,
			'Email' => $email,	
			'Password' => md5($password),
			'User_name' => $username,
			'Streetadress' => $street,
			'Postalcode' => $postalcode,
			'Hometown' => $hometown);
		
		$res = $this->db->insert('User', $new_member_insert_data);
		
		//felhantering om problem att prata med db 
		if($res == false)
		{
			return false;
		}
		return true;
	}
	function update_user($userID,$fname, $lname, $email, $password, $username, $street, $postalcode, $hometown)
	{
		$data = array(
			'First_name' => $fname,
			'Last_name' => $lname,
			'Email' => $email,	
			'Password' => md5($password),
			'User_name' => $username,
			'Streetadress' => $street,
			'Postalcode' => $postalcode,
			'Hometown' => $hometown);

		$this->db->where('UserID', $userID);
		$res = $this->db->update('User', $data);
		if($res == false)
		{
			return false;
		}
		return true;
	}
	function delete_user($userID)
	{
		$res = $this->db->delete('User', array('UserID' => $userID)); 
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
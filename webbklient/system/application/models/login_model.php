<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library User
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Login_model extends Model 
{

	/**
    * 
    * Check if there is a matching username and password 
    * in the db 
	*
    * @param string $username
    * @param string $password
    * @return bool
    */

	function Login($username,$password)
	{
		$this->load->library('user');
		$passwordForm = $this->user->transformPassword($password);
		$query = $this->db->get_where('User', array('User_name' => $username,'Password' => $passwordForm));

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
	        {
				$pers = array('UserID' => $row->UserID, 'login_status' => "online");	
				$this->session->set_userdata($pers);
				return true;
	        }
		}  
		return false;
	}
	
	/**
    * 
    * Check if a user is online or offline
    * 
    * @return bool
    */
	
	function IsLoggedIn()
	{
		$isloggedin = $this->session->userdata('login_status');
		if(!isset($isloggedin) ){	
				$this->session->set_userdata('login_status', 'offline'); 
			return false;
		}
		if($isloggedin == "online"){	
				return true;
		}
		if($isloggedin != "online"){
			return false;
		}
		return false;
	}
	
	/**
    * 
    * Do logout and kill session
    * 
    * @return bool
    */
	function Logout()
	{	
		//$uid = $this->session->userdata('userid');
		$this->session->set_userdata('login_status', 'offline');
		$this->session->sess_destroy();
		return true;
	}
	

}





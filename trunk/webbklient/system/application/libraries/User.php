<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about users including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class User
{ 
	private $_CI = null;

	function __construct()
	{
		// get CI instance
		$this->_CI = & get_instance();
		
		// load model for library
		$this->_CI->load->model('User_model');
	}
	
	function Register($insert)
	{
		return $this->_CI->User_model->insert_user($insert);
	}

}

?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about projects including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Project
{ 
	private $_CI = null;

	function __construct()
	{
		// get CI instance
		$this->_CI = & get_instance();
		
		// load model for library
		$this->_CI->load->model('project_model');
	}

	
	
}

?>
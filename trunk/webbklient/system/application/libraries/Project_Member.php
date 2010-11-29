<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about projects including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Project_Member
{ 
    private $_CI = null;
    private $_last_error = "";

    function __construct()
    {
        // get CI instance

        $this->_CI = & get_instance();

        // load model for library

        $this->_CI->load->model(array('Project_member_model'));
    }

    /**
    * This function will return the last error
    * this class has set.
    */

    function GetLastError()
    {
        // save error, clear message and return

        $returnStr = $this->_last_error;
        $this->_last_error = "";
        return $returnStr;
    }

    /**
    * Function: SelectByUserId
    * This function will diliver the memberships
    * for the logged in user from the project_member_model.
    *
    * @param array $id
    * @return bool
    */

    function SelectByUserId()
    {
        $userID = $this->_CI->session->userdata('UserID');

        $projectMemberID = $this->_CI->Project_member_model->getByUserId($userID);

        if($projectMemberID > 0) {

            return $projectMemberID;
        }

        return false;
    }

}

?>
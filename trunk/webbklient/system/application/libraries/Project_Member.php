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

        $this->_CI->load->model(array('Project_model', 'Project_member_model'));
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

    /**
    * Function: CheckIfExist
    * This function is used in the formvalidation. Searches the
    * database for a match and returns the answer as an bool.
    *
    * @param string $column
    * @param string $value
    * @return bool
    */

    function CheckIfExist($column, $value)
    {
        // Fetches all the projects

        $projects = $this->_CI->Project_model->getAll();

        // Looping the projects to find a match

        foreach($projects as $project) {

            // Search for match

            if($project[$column] == $value) {

                return true;
            }
        }

        return false;
    }

    /**
    * Function: IsMember
    * This function is used in order to see if logged in user
    * is a member in selected project. Searches the
    * database for a match and returns the answer as an bool.
    *
    * @param string $projectID
    * @return bool
    */

    function IsMember($projectID)
    {
        $userID = $this->_CI->session->userdata('UserID');

        // Fetch memberships

        $memberships = $this->_CI->Project_member_model->getByUserId($userID);

        if($memberships != NULL)
        {
            foreach($memberships as $membership) {

                // Search for match

                if($membership['Project_id'] == $projectID) {

                    return true;
                }
            }
        }

        return false;
    }

    /**
    * Function: HaveRole
    * This function is used in order to see if logged in user
    * have a certain role in selected project. Searches the
    * database for a match and returns the answer as an bool.
    *
    * @param string $role
    * @return bool
    */

    function HaveRole($role)
    {
        $userID = $this->_CI->session->userdata('UserID');

        // Fetch memerships

        $memberships = $this->_CI->Project_member_model->getByUserId($userID);

        if($memberships != NULL)
        {
            foreach($memberships as $membership) {

                // Search for match

                if($membership['Role'] == $role) {

                    return true;
                }
            }
        }

        return false;
    }

}

?>
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
    * The parameter $role is case-insensitive but must have
    * the correct spelling. In order to check permissions only
    * the lowest permitted role need to be checked
    * 
    * @param string $role
    * @return bool
    */

    function HaveRoleInCurrentProject($role)
    {
        // Make role name case-insensitiv

        $role = ucfirst(strtolower($role));
        
        // fetch userID

        $userID = $this->_CI->session->userdata('UserID');

        // Fetch memerships

        $memberships = $this->_CI->Project_member_model->getByUserId($userID);

        // Fetch currentProjectID

        $currentProjectID = $this->_CI->session->userdata('current_project_id');

        $roleInProject = null;

        if($memberships != null)
        {
            foreach($memberships as $membership) {

                // Search for match

                if($membership['Role'] == $role && $membership['Project_id'] == $currentProjectID) {

                    return true;
                }
                else if($membership['User_id'] == $userID && $membership['Project_id'] == $currentProjectID)
                {
                    $roleInProject = $membership['Role'];
                }

            }
        }

        // If no match, set up role structure to see if users role is higher or lower in hierarchy

        // Fetch all roles

        $appRoles = $this->_CI->Project_role_model->getAll();

        $roleStructure = array();

        $currentOrder = null;

        // Set up role structure

        while(count($roleStructure) < count($appRoles)) {

            foreach($appRoles as $appRole) {

                if($currentOrder == NULL && $appRole['Project_role_id'] == $appRole['Project_role_id_u']) {

                    $currentOrder = $appRole['Project_role_id'];

                    array_push($roleStructure, $appRole['Role']);

                }
                else if($appRole['Project_role_id_u'] == $currentOrder)
                {
                    $currentOrder = $appRole['Project_role_id'];

                    array_push($roleStructure, $appRole['Role']);
                }

            }
        }

        $roleLevel = null;

        // If users role in role structure is called before permitted role level, return true

        foreach($roleStructure as $roleItem) {

            if($roleItem == $role && $roleLevel == null)
            {
                $roleLevel = $roleItem;
            }
            else if($roleItem == $roleInProject && $roleLevel == null)
            {
                return true;
            }

        }

        return false;
    }

}

?>
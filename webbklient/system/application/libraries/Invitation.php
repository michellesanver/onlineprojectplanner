<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about projects including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Invitation
{ 
    private $_CI = null;
    private $_last_error = "";

    function __construct()
    {
        // get CI instance

        $this->_CI = & get_instance();

        // load model for library

        $this->_CI->load->model(array('Invitation_model'));
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
    * Function: GetSuitableRolesForInvitation
    * This function will return the suitable roles
    * for certain member role to invite
    *
    * @param string $value
    * @return bool
    */

    function GetSuitableRolesForInvitation()
    {
        // Fetches all available roles

        $appRoles = $this->_CI->Project_role_model->getAll();

        if($appRoles != null)
        {
            // Fetch userID

            $userID = $this->_CI->session->userdata('UserID');

            // Fetch memerships

            $memberships = $this->_CI->Project_member_model->getByUserId($userID);

            // Fetch currentProjectID

            $currentProjectID = $this->_CI->session->userdata('current_project_id');

            $roleInProject = null;

            if($memberships != null)
            {
                foreach($memberships as $membership) {

                    if($membership['User_id'] == $userID && $membership['Project_id'] == $currentProjectID)
                    {
                        $roleInProject = $membership['Role'];
                    }

                }
            }

            // Set up role structure to see what roles is equal or lower in hierarchy

            $roleStructure = array();

            $currentOrder = null;

            // Set up role structure with needed information

            while(count($roleStructure) < count($appRoles)) {

                foreach($appRoles as $appRole) {

                    if($currentOrder == null && $appRole['Project_role_id'] == $appRole['Project_role_id_u'])
                    {
                        $currentOrder = $appRole['Project_role_id'];

                        array_push($roleStructure, array('Project_role_id' => $appRole['Project_role_id'], 'Role' => $appRole['Role']));

                    }
                    else if($appRole['Project_role_id_u'] == $currentOrder)
                    {
                        $currentOrder = $appRole['Project_role_id'];

                        array_push($roleStructure, array('Project_role_id' => $appRole['Project_role_id'], 'Role' => $appRole['Role']));
                    }

                }
            }

            $roleLevel = null;

            // If users role in role structure is called, get role and following roles in structure

            $suitableRoles = array();

            foreach($roleStructure as $roleItem) {

                if($roleItem['Role'] == $roleInProject)
                {
                    $roleLevel = $roleItem['Role'];

                    if($roleItem['Role'] != ucfirst(strtolower('General')))
                    {
                        array_push($suitableRoles, array('Project_role_id' => $roleItem['Project_role_id'], 'Role' => $roleItem['Role']));
                    }
                }
                else if($roleLevel != null)
                {
                    array_push($suitableRoles, array('Project_role_id' => $roleItem['Project_role_id'], 'Role' => $roleItem['Role']));
                }

            }

            if($suitableRoles != null)
            {
                return $suitableRoles;
            }
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

        $invitations = $this->_CI->invitation_model->getAll();

        // Looping the projects to find a match

        foreach($invitations as $invitation) {

            // Search for match

            if($invitation[$column] == $value) {

                return true;
            }
        }

        return false;
    }

}

?>
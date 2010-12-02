<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about projects including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Project_lib
{ 
    private $_CI = null;
    private $_last_error = "";

    function __construct()
    {
        // get CI instance

        $this->_CI = & get_instance();

        // load model for library

        $this->_CI->load->model(array('Project_model', 'Project_member_model', 'Project_role_model'));
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
    * Will check if any project is set
    * as current. If not false is returned
    * or else the Project_Id
    * 
    * @return mixed
    */
    function checkCurrentProject()
    {
        // if not set false will be returned
        return $this->_CI->session->userdata('current_project_id');    
    }
    
    /**
    * This will set current project (trigger
    * for the class theme for example)
    * 
    * @param int $projectID
    */
    function setCurrentProject($projectID)
    {
        $this->_CI->session->set_userdata('current_project_id', $projectID);    
    }
    
    /**
    * Clear the current set project
    */
    function clearCurrentProject()
    {
        $this->_CI->session->unset_userdata('current_project_id');     
    }
    
    /**
    * Function: Select
    * This function will diliver the old registrated
    * information to from the project_model.
    *
    * @param array $id
    * @return bool
    */

    function Select($id)
    {
        $projectID = $this->_CI->Project_model->getById($id);

        if($projectID > 0) {

            return $projectID;
        }

        return false;
    }

    /**
    * Function: Register
    * This function will diliver the validated registration
    * information to the project_model.
    *
    * @param array $insert
    * @return bool
    */

    function Register($insert)
    {
        $userID = $this->_CI->session->userdata('UserID');

        $role = $this->_CI->Project_role_model->getByRole(ucfirst(strtolower('Admin')));

        $result = $this->_CI->Project_model->insert($insert, $userID, $role);

        if($result) {

            return true;

        }

        return false;

    }

    /**
    * Function: Update
    * This function will diliver the validated update
    * information to the project_model.
    *
    * @param array $update
    * @return bool
    */

    function Update($update)
    {
        $projectID = $this->_CI->Project_model->update($update);

        if($projectID > 0) {

            return true;
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

}

?>
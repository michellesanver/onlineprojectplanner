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

        $result = $this->_CI->Project_model->insert($insert, $userID);

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
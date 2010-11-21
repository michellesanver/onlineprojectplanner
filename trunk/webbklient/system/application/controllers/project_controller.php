<?php

/*
* Class Project_controller
*/

class Project_controller extends Controller {

    function Project_controller()
    {
        parent::Controller();

        $this->load->library(array('validation'));
    }

    /**
    * Function: Register
    *
    * Description: Will show the project/register.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Register()
    {
        // Rules for the inputfields

        $rules = array (
            "title" => "required|max_length[100]|xss_clean|callback_title_check",
            "description" => "required|max_length[300]|xss_clean"
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "title" => "Title",
            "description" => "Description"
        );

        $this->validation->set_fields($field);

        $status = $this->validation->run();

        $data = array();

        if($status) {

            $insert = array(
                    "Title" => $this->validation->title,
                    "Description" => $this->validation->description
            );

            // If validation is ok => send to library

            if($this->project->Register($insert)) {

                $data = array(
                        "status" => "ok",
                        "status_message" => "Registration was successful!"
                );
            }
        }

        if($status == false && isset($_POST['register_btn'])) {

            $data = array(
                "title" => $this->validation->title,
                "description" => $this->validation->description,
                "status" => "error",
                "status_message" => "Registration failed!"
            );
        }

        $this->load->view('project/register', $data);

    }

    function Update($projectID = NULL)
    {

        // Get saved data

        $savedData = $this->project->Select($projectID);

        // If saved data exists

        if($savedData) {

            // Rules for the inputfields

            $rules = array (
                "projectID" => "required|integer",
                "description" => "required|max_length[300]|xss_clean"
            );

            $this->validation->set_rules($rules);

            // Human names for the inputfields

            $field = array(
                "projectID" => "ProjectID",
                "description" => "Description"
            );

            $this->validation->set_fields($field);

            $status = $this->validation->run();

            $data = array();

            if($status) {

                $update = array(
                        "ProjectID" => $this->validation->projectID,
                        "Description" => $this->validation->description
                );

                // If validation is ok => send to library

                if($this->project->Update($update)) {

                    $data = array(
                            "projectID" => $this->validation->projectID,
                            "title" => $savedData['Title'],
                            "description" => $this->validation->description,
                            "status" => "ok",
                            "status_message" => "Update was successful!"
                    );
                }
            }
            else if($status == false && isset($_POST['update_btn'])) {

                $data = array(
                    "projectID" => $this->validation->projectID,
                    "title" => $savedData['Title'],
                    "description" => $this->validation->description,
                    "status" => "error",
                    "status_message" => "Update failed!"
                );
            }
            else {

                $data = array(
                    "projectID" => $savedData['ProjectID'],
                    "title" => $savedData['Title'],
                    "description" => $savedData['Description'],
                );
            }

        }

        $this->load->view('project/update', $data);

    }

    /**
    * Function: title_check
    * This function is part of the register validation. It will stop any
    * registration with an title that already exist
    *
    *@param string $str
    *@return bool
    */

    function title_check($str)
    {
        if($this->project->CheckIfExist("Title", $str) == true) {

            $this->validation->set_message('title_check', 'That project title already exist in our database.');
            return false;
        }

        return true;
    }

}

/* End of file project_controller.php */
/* Location: ./system/application/controllers/project_controller.php */
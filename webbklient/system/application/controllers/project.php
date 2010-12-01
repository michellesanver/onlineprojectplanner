<?php

/*
* Class Project
*/

class Project extends Controller {

    function __construct()
    {
        parent::Controller();

        $this->load->library(array('validation'));
        $this->load->library('project_lib', null, 'project');
        $this->load->library('project_member');
        $this->load->model('project_model');
    }

    /**
    * Function: Register
    *
    * Description: Will show the project/register.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Register()
    {
        // If user is logged in

        if($this->user->IsLoggedIn()) {

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

            // If have status

            if($status) {

                // Set inserts

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

                // Else, if something went wrong

                else {

                    $data = array(
                            "status" => "error",
                            "status_message" => "Registration failed!"
                    );
                }
            }

            // If no status but post

            if($status == false && isset($_POST['register_btn'])) {

                $data = array(
                    "title" => $this->validation->title,
                    "description" => $this->validation->description,
                    "status" => "error",
                    "status_message" => "Registration failed!"
                );
            }

            $this->theme->view('project/register', $data);

        }

        // Else, redirect to login page

        else {

            redirect('account/login');

        }
    }

    /**
    * Function: Update
    *
    * Description: Will show the project/update.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Update($projectID = NULL)
    {
        // If user is logged in

        if($this->user->IsLoggedIn()) {

            $data = array();

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
                    "projectID" => "Project_id",
                    "description" => "Description"
                );

                $this->validation->set_fields($field);

                $status = $this->validation->run();

                // If have status

                if($status) {

                    // Set updates

                    $update = array(
                            "Project_id" => $this->validation->projectID,
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

                    // Else, if something went wrong

                    else {

                        $data = array(
                                "projectID" => $this->validation->projectID,
                                "title" => $savedData['Title'],
                                "description" => $this->validation->description,
                                "status" => "error",
                                "status_message" => "Update failed!"
                        );
                    }

                }

                // If no status but post

                else if($status == false && isset($_POST['update_btn'])) {

                    $data = array(
                        "projectID" => $this->validation->projectID,
                        "title" => $savedData['Title'],
                        "description" => $this->validation->description,
                        "status" => "error",
                        "status_message" => "Update failed!"
                    );
                }

                // Else, present saved data

                else {

                    $data = array(
                        "projectID" => $savedData['Project_id'],
                        "title" => $savedData['Title'],
                        "description" => $savedData['Description'],
                    );
                }

            }

            $this->theme->view('project/update', $data);

        }

        // Else, redirect to login page

        else {

            redirect('account/login');

        }
    }

    /**
    * Function: Delete
    *
    * Description: Will show the project/delete.php view and
    * catch the Project_id from get.
    * In order to delete a project the logged user need to be
    * a member of the selected project
    */

    function Delete($projectID = NULL)
    {
        if($this->user->IsLoggedIn()) {

            $data = array();

            // If user have membership in selected project

            if($this->project_member->IsMember($projectID)) {

                // And if user have the Admin-role in selected project

                if($this->project_member->HaveRole('Admin')) {

                    // If validation is ok => send to library

                    if($this->project_model->Delete($projectID)) {

                        $data = array(
                                "status" => "ok",
                                "status_message" => "Delete was successful!"
                        );
                    }

                    // Else, if something went wrong

                    else {

                        $data = array(
                                "status" => "error",
                                "status_message" => "Delete failed!"
                        );
                    }

                }
                else {

                    $data = array(
                            "status" => "error",
                            "status_message" => "You need to be an Admin for this action!"
                            );
                }


            }
            else {

                $data = array(
                        "status" => "error",
                        "status_message" => "You are not a member of this project!"
                        );
            }

            $this->theme->view('project/delete', $data);

        }

        // Else, redirect to login page

        else {

            redirect('account/login');

        }
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
    
    /**
     * Lists all the users projects so that they can choose one.
     */
    function index()
    {	
        
    	$this->load->model('project_member_model');
    	$this->load->model('project_model');
    	
    	$allProjects = $this->project_member_model->getByUserId($this->session->userdata('UserID'));
    	$projects = array();
    	
    	if(!is_null($allProjects)) {
    		foreach($allProjects as $project) {
    			$projects[] = $this->project_model->getById($project["Project_id"]);
    		}
    	}
    
    	$data["projects"] = $projects;
    	$this->theme->view('project/index', $data);
    }
    
    /**
     * Shows a certain project (first function to be called in the project)
     */
	function view($projectID)
	{
		
        // save current projectID (will be catched in class theme)
        $this->session->set_userdata('current_project_id', $projectID);    
        
        // proceed to view
        $this->theme->view('project/start');
        
	}
    
    /**
    * Called from project_pre_contant and sets common javascript variables
    * (clientside javascript generated by serverside php)
    */
    function common_variables()
    {
        // package data
        $data = array(
            'base_url' => $this->config->item('base_url'),
            'site_url' => site_url()
        );
        
        // load and output view (NO theme needed)
        $this->load->view($this->theme->GetThemeFolder().'/common/js_variables', $data);    
    }
    
}

/* End of file project_controller.php */
/* Location: ./system/application/controllers/project_controller.php */
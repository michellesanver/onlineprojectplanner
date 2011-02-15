<?php

// class can be changed as needed
class ps_controller extends Controller {

	// the class is a regular Codeigniter controller
	// and inherits from CI
	function __construct()
	{
		parent::Controller();
		$this->load->library(array('validation', 'project_lib'));
		$this->load->model(array("project_model"));
	}
	
	/**
	* Ajax-request function.
	* Displays the first settingspage of the project
	*
	* @param int $Pid
	*/
	function index($pwID, $Pid)
	{
		
		// add a tracemessage to log
		log_message('debug','#### => Controller ps_controller->index');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		// If User is not logged in
		if($this->user->IsLoggedIn()==false)
		{
			echo "You are not authenticated! <a href=\"".site_url('')."\">Login</a>";
			return;
		}

		// Is user is not member in selected project
		if($this->project_member->IsMember($Pid)==false)
		{
			echo "You are not a member of this project! <a href=\"".site_url('')."\">Take me back.</a>";
			return;
		}

		// See if user is Admin in selected project
		if($this->project_member->HaveRoleInCurrentProject('admin') == false)
		{
			echo "You are not a admin of this project! <a href=\"".site_url('')."\">Take me back.</a>";
			return;
		}
		
        $data = $this->project_lib->Select($Pid);
		$data['pwID'] = $pwID;
		
		$this->load->view_widget('index', $data);
	}
	
	/**
	* Ajax-request function.
	* Validates and saves the new description.
	*/
	function saveDescription() {
		// add a tracemessage to log
		log_message('debug','#### => Controller ps_controller->saveDescription');
		
		$Pid = (int)$_POST['Project_id'];
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}

		// If User is not logged in
		if($this->user->IsLoggedIn()==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not authenticated. Please login!"));
			return;
		}

		// Is user is not member in selected project
		if($this->project_member->IsMember($Pid)==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not a member of this project"));
			return;
		}

		// See if user is Admin in selected project
		if($this->project_member->HaveRoleInCurrentProject('admin') == false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not a admin of this project! <a href=\"".site_url('')."\">Take me back.</a>"));
			return;
		}
		
		// Rules for the inputfields
		$this->validation->set_rules(array("Description" => "required|max_length[300]|xss_clean"));
		
		// Human names for the inputfields
		$this->validation->set_fields(array("Description" => "Description"));
		
		$data = array();
		
		if($this->validation->run()) {
			// Set updates
			$update = array(
				"Project_id" => $Pid,
				"Description" => $this->validation->Description
			);
			
			// If validation is ok => send to library
			if($this->project_lib->Update($update)) {
				$data["status"] = "ok";
				$data["status_message"] = "Update was successful!";
				$data["load"] = "index";
			} else {
				$data["status"] = "error";
				$data["status_message"] = "Update failed!";
			}
		} else {
			$data["status"] = "error";
			$data["status_message"] = "Validation failed! <p>".$this->validation->error_string."</p>";
		}
		
		echo json_encode($data);
	}
	
    /**
	* Ajax-request function.
    * Delete the current project.
    */

	function delete($projectID)
	{
		// Add a tracemessage to log
		log_message('debug','#### => Controller ps_controller>delete');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}

		// If User is not logged in
		if($this->user->IsLoggedIn()==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not authenticated. Please login!"));
			return;
		}

		// Is user is not member in selected project
		if($this->project_member->IsMember($projectID)==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not a member of this project"));
			return;
		}

		// See if user is Admin in selected project
		if($this->project_member->HaveRoleInCurrentProject('general') == false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not an projectgeneral."));
			return;
		}
		
        // if any project is set; clear variable (project will not exist after this function)
        $this->project_lib->clearCurrentProject();
		
        // Delete project
        if($this->project_model->Delete($projectID)) {
            $data = array(
				"status" => "ok",
				"status_message" => "Delete was successful!",
				"load" => "blockUser"
            );
        } else {
            $data = array(
				"status" => "error",
				"status_message" => "Delete failed!"
            );
			$this->error->log('Project delete failed.', $_SERVER['REMOTE_ADDR'], 'Project/Delete', 'project_model/Delete', array('Project_id' => $projectID));
        }
		
		echo json_encode($data);
	}
}

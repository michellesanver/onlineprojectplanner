<?php

// class can be changed as needed
class pm_controller extends Controller {

	// the class is a regular Codeigniter controller
	// and inherits from CI
	function __construct()
	{
		parent::Controller();
		$this->load->library(array('project_member', 'validation', 'emailsender', 'invitation', 'project_lib'));
		$this->load->model(array("project_model", "Invitation_model", "Project_member_model", "Project_role_model"));
	}
	
	/**
	* Displays a invitation form and a list of all the members.
	* If postdata is cought, it will handle that.
	*
	* @param int $Pid
	*/
	function index($Pid, $pwID)
	{
		// add a tracemessage to log
		log_message('debug','#### => Controller pm_controller->index');
		
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

		// See if user is General in selected project
		$isGeneral = false;

		if($this->project_member->HaveSpecificRoleInCurrentProject('General') != false)
		{
			$isGeneral = true;
		}
		
		// If any project is set, clear current variable
		$this->project_lib->clearCurrentProject();

		// Set current projectID (will be catched in class theme)
		$this->project_lib->setCurrentProject($Pid);

		// Get project information
		$project = $this->project_model->getById($Pid);

		// Get project members information
		$projectMembers = $this->project_member->GetMembersByProjectId($Pid);

		// Get project roles allowed for invitation
		$projectRoles = $this->invitation->GetSuitableRolesForInvitation();
		
    // proceed and show view
		$data["projectID"] = $project['Project_id'];
		$data["title"] = $project['Title'];
		$data["pwID"] = $pwID;
		$data["members"] = $projectMembers;
		$data["roles"] = $projectRoles;
		$data["isGeneral"] = $isGeneral;
		
		$this->load->view_widget('pm_index', $data);
	}
	
	/*
	* Catches invitation post-data and executes the needed functions
	* for the invitation email to be sant.
	*/
	function save() {
		// add a tracemessage to log
		log_message('debug','#### => Controller pm_controller->save');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		if(isset($_POST['projectID'])) {
			$Pid = $this->input->post('projectID');
		} else {
			echo json_encode(array("status" => "error", "status_message" => "An error has occurred with the project."));
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
		
		$data = array();
		
		// Rules for the inputfields
		$this->validation->set_rules(array("email" => "required|valid_email"));
		
		// Human names for the inputfields
		$this->validation->set_fields(array("email" => "Email"));
		
		if($this->validation->run()) {
			// Set invitation
			// Create invitation code
			$code = "";

			for($n = 0; $n < 10; $n++) {
				switch (rand(1,3)) {
					// numbers
					case 1: $code .= chr( rand(49,57) ); break;

					// lowercase letter
					case 2: $code .= chr( rand(65,90) ); break;

					// uppercase letter
					case 3: $code .= chr( rand(97,122) ); break;
				}
			}

			// encrypt (hash) code
			$encryptedCode = md5('myinvitation'.$code);
			
			$invitation = array(
				"Code" => $encryptedCode,
				"Project_id" => $_POST['projectID'],
				"Project_role_id" => $_POST['projectRoleID']
			);
			
			// If validation is ok => send to library
			$invitationId = $this->Invitation_model->insert($invitation);;
			
			if($invitationId > 0) {
				// Send an invitation by email
				$email = (isset($this->validation->email)) ? $this->validation->email : "";
				if($email == "" || $this->emailsender->SendInvitationMail($email, $encryptedCode) == false) {
					$data = array(
						"status" => "error",
						"status_message" => "Failed to send invitation email"
					);
					$this->Invitation_model->delete($invitationId);
				} else {
					$data = array(
						"status" => "ok",
						"status_message" => "Invite was successful!"
					);
				}
			}

			// Else, if something went wrong
			else {
				$data = array(
					"status" => "error",
					"status_message" => "Invite failed!"
				);
				$this->error->log('Project invitation failed.', $_SERVER['REMOTE_ADDR'], 'Project/Members', 'project/Invite', $invitation);
			}
		} else {
			$data = array(
				"status" => "error",
				"status_message" => "Failed to send invitation email"
			);
		}
	 echo json_encode($data);
	}
	
    /**
    * In order to kick someone out of the project the logged user need to be
    * the General of the selected project
    */

	function kickOut($victimID, $projectID)
	{
		// Add a tracemessage to log
		log_message('debug','#### => Controller pm_controller->kickOut');
		
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

		// Is logged in user General of selected project?
		if ($this->project_member->HaveRoleInCurrentProject('general')==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not an project general."));
			return;
		}

		// Is kick out victim member in the project?
		if($this->project_member->IsVictimMember($victimID, $projectID)==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "The member you want to kick out is not a member of this project."));
			return;
		}

		// Is General schizophrenic?
		if($this->project_member->IsGeneralSchizophrenic($victimID) != false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You can not kick out yourself!"));
			return;
		}

		$data = array();

		// Kick out victim
		if($this->Project_member_model->delete($projectID, $victimID)) {
			$data = array(
				"status" => "ok",
				"status_message" => "Member is kicked out!",
				"load" => "PM_index"
			);
		}
		
		// Else, if something went wrong
		else {
			$data = array(
				"status" => "error",
				"status_message" => "Something went wrong, the user is still a member of the project!"
			);
			$this->error->log('Kick out member failed.', $_SERVER['REMOTE_ADDR'], 'Project/KickOut', 'project_member/Delete', array('Project_id' => $userID, 'User_id' => $victimID));
		}

		echo json_encode($data);
	}
	
    /**
    * In order to make someone a General of the project the logged user need to be
    * the General himself/herself of the selected project
    */

	function switchGeneral($victimID, $projectID)
	{
		// Add a tracemessage to log
		log_message('debug','#### => Controller pm_controller->switchGeneral');
		
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

		// Is logged in user General of selected project?
		if ($this->project_member->HaveRoleInCurrentProject('general')==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not an project general."));
			return;
		}

		// Is kick out victim member in the project?
		if($this->project_member->IsVictimMember($victimID, $projectID)==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "The member you want to kick out is not a member of this project."));
			return;
		}

		// Is General schizophrenic?
		if($this->project_member->IsGeneralSchizophrenic($victimID) != false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You can not kick out yourself!"));
			return;
		}

		$data = array();
		
		$userID = $this->session->userdata('UserID');
		$generalRole = $this->Project_role_model->getByRole(ucfirst(strtolower('General')));
		$adminRole = $this->Project_role_model->getByRole(ucfirst(strtolower('Admin')));
		
		// SwitchGeneral
		if($this->Project_member_model->switchGeneral($projectID, $userID, $victimID, $adminRole, $generalRole)) {
			$data = array(
				"status" => "ok",
				"status_message" => "You are no longer the general of this project!",
				"load" => "PM_index"
			);
		} else {
			$data = array(
				"status" => "error",
				"status_message" => "Something went wrong, you are still the general of this project!"
			);
			$this->error->log('Switch General failed.', $_SERVER['REMOTE_ADDR'], 'Project/SwitchGeneral');
		}

		echo json_encode($data);
	}

	/**
	* In order to leave a project the logged user need to be
	* a member of the selected project and NOT a General.
	*/

	function leave($projectID)
	{
		// Add a tracemessage to log
		log_message('debug','#### => Controller Project->Leave');
		
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

		// If any project is set, clear current variable
		$this->project_lib->clearCurrentProject();

		$data = array();
		$userID = $this->session->userdata('UserID');
		
		// Make user leave
		if($this->Project_member_model->delete($projectID, $userID)) {
			$data = array(
				"status" => "ok",
				"status_message" => "You have left the project!",
				"load" => "PM_index"
			);
		} else {
			$data = array(
				"status" => "error",
				"status_message" => "Something went wrong, you are still a member of the project!"
			);
			$this->error->log('Leave project failed.', $_SERVER['REMOTE_ADDR'], 'Project/Leave', 'project_member/Delete', array('Project_id' => $projectID));
		}
		
		echo json_encode($data);
	}
	
	/*
	* Executed with a ajax-request when the general 
	* has clicked the "Promote to admin" link in the
	* project member list. 
	*
	* @param $proj_mem_id
	*/
	function promoteToAdmin($proj_mem_id, $projectID) {
		
		// Add a tracemessage to log
		log_message('debug','#### => Controller Project->promoteToAdmin');
		
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

		// Is logged in user General of selected project?
		if ($this->project_member->HaveRoleInCurrentProject('general')==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not an project general."));
			return;
		}
		
		if($this->Project_member_model->switchRole($proj_mem_id, 1)) {
			$data = array(
				"status" => "ok",
				"status_message" => "The user has been promoted to admin!",
				"load" => "PM_index"
			);
		} else {
			$data = array(
				"status" => "error",
				"status_message" => "An error occurred while trying to promote!"
			);
		}
		
		echo json_encode($data);
	}
	
	/*
	* Executed with a ajax-request when the general 
	* has clicked the "Demote to member" link in the
	* project member list. 
	*
	* @param $proj_mem_id
	*/
	function demoteToMember($proj_mem_id, $projectID) {
		
		// Add a tracemessage to log
		log_message('debug','#### => Controller Project->promoteToAdmin');
		
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

		// Is logged in user General of selected project?
		if ($this->project_member->HaveRoleInCurrentProject('general')==false)
		{
			echo json_encode(array("status" => "error", "status_message" => "You are not an project general."));
			return;
		}
		
		if($this->Project_member_model->switchRole($proj_mem_id, 2)) {
			$data = array(
				"status" => "ok",
				"status_message" => "The user has been promoted to admin!",
				"load" => "PM_index"
			);
		} else {
			$data = array(
				"status" => "error",
				"status_message" => "An error occurred while trying to promote!"
			);
		}
		
		echo json_encode($data);
	}
}

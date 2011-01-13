<?php

// class can be changed as needed
class pm_controller extends Controller {

	// the class is a regular Codeigniter controller
	// and inherits from CI
	function __construct()
	{
		parent::Controller();
		$this->load->library(array('project_member', 'emailsender', 'invitation', 'project_lib'));
		$this->load->model(array("project_model", "Invitation_model"));
	}
	
	// first function to be called if not specified in URL (Codeigniter)
	function index($Pid)
	{
		
		// add a tracemessage to log
		log_message('debug','#### => Controller Project->Members');
		
		// If User is not logged in
		if($this->user->IsLoggedIn()==false)
		{
			echo "login_error";
			return;
		}

		// Is user is not member in selected project
		if($this->project_member->IsMember($Pid)==false)
		{
			echo "member_error";
			return;
		}

		// See if user is General in selected project
		$isGeneral = false;

		if($this->project_member->HaveSpecificRoleInCurrentProject('General') != false)
		{
			$isGeneral = true;
		}
		
		if(count($_POST) > 0) {
			$post = $_POST;
			
			// Set invitation
			// Create invitation code
			$code = "";

			for($n = 0; $n < 10; $n++)
			{
				switch (rand(1,3))
				{
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
				"Project_id" => $post['projectID'],
				"Project_role_id" => $post['projectRoleID']
			);
			//var_dump($invitation);
			// If validation is ok => send to library
			$invitationId = $this->Invitation_model->insert($invitation);;

			if($invitationId > 0)
			{
				// Send an invitation by email

				if($this->emailsender->SendInvitationMail($post['email'], $encryptedCode) == false)
				{
					$data = array(
						"status" => "error",
						"status_message" => "Failed to send invitation email"
					);

					$status = false;

					$this->invitation_model->delete($invitationId);
				}
				else
				{
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
		$data["members"] = $projectMembers;
		$data["roles"] = $projectRoles;
		$data["isGeneral"] = $isGeneral;
		
		$this->load->view_widget('index', $data);
	}
  
	function save() {
		$post = $_POST;
		
		// Set invitation
		// Create invitation code
		$code = "";

		for($n = 0; $n < 10; $n++)
		{
			switch (rand(1,3))
			{
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
			"Project_id" => $post['projectID'],
			"Project_role_id" => $post['projectRoleID']
		);
		
		// If validation is ok => send to library
		$invitationId = $this->Invitation_model->insert($invitation);;

		if($invitationId > 0)
		{
			// Send an invitation by email

			if($this->emailsender->SendInvitationMail($post['email'], $encryptedCode) == false)
			{
				$data = array(
					"status" => "error",
					"status_message" => "Failed to send invitation email"
				);

				$status = false;

				$this->invitation_model->delete($invitationId);
			}
			else
			{
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
		
		$this->load->view_widget('index', $data);
	}
}

<?php

/*
* Class Project
*/

class Project extends Controller {
    
    function __construct()
    {
        parent::Controller();

        $this->load->library(array('validation', 'project_member', 'emailsender', 'invitation'));
        $this->load->library('project_lib', null, 'project', 'widgets');
        $this->load->model('project_model');
        $this->load->model('project_member_model');
        $this->load->model('project_role_model');
        $this->load->model('invitation_model');
        $this->load->model('widgets_model');
    }

    /**
    * Description: Will show the project/register.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Register()
    {
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->Register');
        
        // is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');
            
            // no, redirect   
            redirect('account/login');
            return;
        }
        
        
        // ----------------------------------
        // continue
        
        
        // if any project is set; clear variable
        $this->project->clearCurrentProject();
        

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
								$this->error->log('Project registration failed.', $_SERVER['REMOTE_ADDR'], 'Project/Register', 'project/Register', $insert);
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

    /**
    * Description: Will show the project/update.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Update($projectID, $widgetid = 0, $deleteid = 0)
    {
    
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->Update');
        
        // is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');
            
            // no, redirect   
            redirect('account/login');
            return;
        }
        
        // is user member in the project?
        if($this->project_member->IsMember($projectID)==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');
   
            // show project start
            redirect("project/view/$projectID"); 
            return;
        }
        
        // is user admin?
        if ($this->project_member->HaveRoleInCurrentProject('admin')==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication You are not an administrator.');    
            
            // show project start
            redirect("project/view/$projectID"); 
            return; 
        }
        
        if(isset($_POST['cancel_btn'])) {
        	// show project start
            redirect("project/view/$projectID"); 
            return;
        }
        
        // ----------------------------------
        // continue
		if($widgetid != 0) {
    		//Add widget to current project
    		$this->widgets_model->AddProjectWidget($projectID, $widgetid);	
    		
    		//Go back to update
    		redirect("project/update/$projectID"); 
            return; 
    	} else if($deleteid != 0) {
    		//Delete widget from current project
    		$this->widgets_model->DeleteProjectWidget($deleteid);	
    		
    		//Go back to update
    		redirect("project/update/$projectID"); 
            return; 
    	}
    	
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
                            "allwidgets" => $this->widgets->GetAllIconsAsArray(),
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
                            "allwidgets" => $this->widgets->GetAllIconsAsArray(),
                            "status_message" => "Update failed!"
                    );
										$this->error->log('Project update failed.', $_SERVER['REMOTE_ADDR'], 'Project/Update', 'project/Update', $update);
                }

            }

            // If no status but post

            else if($status == false && isset($_POST['update_btn'])) {

                $data = array(
                    "projectID" => $this->validation->projectID,
                    "title" => $savedData['Title'],
                    "description" => $this->validation->description,
                    "status" => "error",
                    "allwidgets" => $this->widgets->GetAllIconsAsArray(),
                    "status_message" => "Update failed!"
                );
            }

            // Else, present saved data

            else {
				$widget_data = array(
					"delete_icons" => $this->widgets->GetProjectIconsAsArray($savedData['Project_id']),
					"projectID" => $savedData['Project_id']
				);
				
                $data = array(
                    "projectID" => $savedData['Project_id'],
                    "title" => $savedData['Title'],
                    "description" => $savedData['Description'],
                    "allwidgets" => $this->widgets->GetAllIconsAsArray(),
                    "custom_bar" => $this->load->view($this->theme->GetThemeFolder() . '/widgets/delete_bar', $widget_data, true)
                );
            }

        }

        $this->theme->view('project/update', $data);
    }

    /**
    * Description: Will show the project/delete.php view and
    * catch the Project_id from get.
    * In order to delete a project the logged user need to be
    * a member of the selected project
    */

    function Delete($projectID)
    {   
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->Delete');
        
        // is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');
            
            // no, redirect   
            redirect('account/login');
            return;
        }
        // is user member in the project?
        if($this->project_member->IsMember($projectID)==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');
   
            // show project start
            redirect("project/view/$projectID");
            return;
        }
        // is user general?
        if ($this->project_member->HaveRoleInCurrentProject('general')==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication You are not an project general.');
            
            // show project start
            redirect("project/view/$projectID");
            return; 
        }
        
        
        // ----------------------------------
        // continue
        
        
        // if any project is set; clear variable (project will not exist after this function)
        $this->project->clearCurrentProject();
        

        $data = array();

        // Delete project

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
						$this->error->log('Project delete failed.', $_SERVER['REMOTE_ADDR'], 'Project/Delete', 'project_model/Delete', array('Project_id' => $projectID));
        }

       $this->theme->view('project/delete', $data);
    }

    /**
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
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->index');
        
        // is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');
            
            // no, redirect   
            redirect('account/login');
            return;
        }
        
        // ----------------------------------
        // continue
        
        // if any project is set; clear variable
        $this->project->clearCurrentProject();
        
        // continue...
    	$this->load->model('project_member_model');
    	$this->load->model('project_model');
    	
    	$allProjects = $this->project_member_model->getByUserId($this->session->userdata('UserID'));
    	$projects = array();
    	
    	if(!is_null($allProjects)) {
    		foreach($allProjects as $project) {
    			$projects[] = $this->project_model->getById($project["Project_id"]);
    		}
    	}
    
        // any error message from authentication error?
        $error_message = $this->session->userdata('errormessage');
        if ($error_message!=false && $error_message != "")
        {
            $data['status'] = 'error';
            $data['status_message'] = $error_message;
            $this->session->unset_userdata('errormessage');
        }
        // any other message?
        $ok_message = $this->session->userdata('message');
        if ($ok_message!=false && $ok_message != "")
        {
            $data['status'] = 'ok';
            $data['status_message'] = $ok_message;
            $this->session->unset_userdata('message');
        }
        
        // proceed and show view
    	$data["projects"] = $projects;
    	$this->theme->view('project/index', $data);
    }
    
    /**
     * Shows a certain project (first function to be called in the project)
     */
	function view($projectID)
	{
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->view');
        
        // is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');
            
            // no, redirect   
            redirect('account/login');
            return;
        }
        // is user member in the project?
        if($this->project_member->IsMember($projectID)==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');
   
            // show project list
            redirect("project");
            return;
        }
        
        // ----------------------------------
        // continue
        
        $data = array();
        
        // any error message from authentication error?
        $error_message = $this->session->userdata('errormessage');
        if ($error_message!=false && $error_message != "")
        {
            $data['status'] = 'error';
            $data['status_message'] = $error_message;
            $this->session->unset_userdata('errormessage');
        }
        // any other message?
        $ok_message = $this->session->userdata('message');
        if ($ok_message!=false && $ok_message != "")
        {
            $data['status'] = 'ok';
            $data['status_message'] = $ok_message;
            $this->session->unset_userdata('message');
        }
        
        // save current projectID (will be catched in class theme)
        $this->project->setCurrentProject($projectID);    
        
        // proceed to view
        $this->theme->view('project/start', $data);
        
	}

    /**
     * Lists all members and handle invites to
     * selected project
		 * 
		 * NOT IN USE
     */

    function Members($projectID)
    {
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->Members');
        
        // If User is not logged in
        if($this->user->IsLoggedIn()==false)
        {
            // Set errormessage (will be catched in account/login)

            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');

            // Redirect to login

            redirect('account/login');
            return;
        }

        // Is user is not member in selected project

        if($this->project_member->IsMember($projectID)==false)
        {
            // Set errormessage (will be catched in project/view)

            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');

            // Redirect to view

            redirect("project/view/$projectID");
            return;
        }

        // See if user is General in selected project

        $isGeneral = false;

        if($this->project_member->HaveSpecificRoleInCurrentProject('General') != false)
        {
            $isGeneral = true;
        }

        // If any project is set, clear current variable

        $this->project->clearCurrentProject();

        // Set current projectID (will be catched in class theme)

        $this->project->setCurrentProject($projectID);

        // Get project information

    	$project = $this->project_model->getById($projectID);

        // Get project members information

        $projectMembers = $this->project_member->GetMembersByProjectId($projectID);

        // Get project roles allowed for invitation

    	$projectRoles = $this->invitation->GetSuitableRolesForInvitation();

        // Rules for the inputfields

        $rules = array (
            "email" => "trim|required|max_length[100]|xss_clean|valid_email",
            "projectID" => "required|integer",
            "projectRoleID" => "required|integer",
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "email" => "Email",
            "projectID" => "Project_id",
            "projectRoleID" => "Project_role_id"
        );

        $this->validation->set_fields($field);

        $status = $this->validation->run();

        $data = array();

        // If have status

        if($status) {

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
                    "Project_id" => $this->validation->projectID,
                    "Project_role_id" => $this->validation->projectRoleID
            );

            // If validation is ok => send to library

            $invitationId = $this->project->Invite($invitation);

            if($invitationId > 0)
            {
                // Send an invitation by email

                if($this->emailsender->SendInvitationMail($this->validation->email, $encryptedCode) == false)
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

        // If no status but post

        else if($status == false && isset($_POST['invite_btn'])) {

            $data = array(
                "status" => "error",
                "status_message" => "Invite failed!"
            );
        }

        // proceed and show view

        $data["projectID"] = $project['Project_id'];
        $data["title"] = $project['Title'];
    	$data["members"] = $projectMembers;
        $data["roles"] = $projectRoles;
        $data["isGeneral"] = $isGeneral;

    	$this->theme->view('project/members', $data);
    }

    /**
    * Description: Will show the project/accept.php view and
    * catch the formvalues if the submit button is clicked.
    */

    function Accept()
    {
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->Accept');
        
        // If User is not logged in
        if($this->user->IsLoggedIn()==false)
        {
            // Set errormessage (will be catched in account/login)

            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');

            // Redirect to login

            redirect('account/login');
            return;
        }

        // if any project is set; clear variable
        $this->project->clearCurrentProject();


        // Rules for the inputfields

        $rules = array (
            "code" => "required|max_length[32]|xss_clean|callback_invite_check"
        );


        $this->validation->set_rules($rules);
        
        // Human names for the inputfields

        $field = array(
            "code" => "Code"
        );

        $this->validation->set_fields($field);
        
        $status = $this->validation->run();

        $data = array();

        // If have status

        if($status) {

            $invitation = $this->invitation_model->getWithCode($this->validation->code);

            // If user not already is a member in selected project

            if($this->project_member->IsMember($invitation['Project_id']) == false)
            {
                // If validation is ok => send to library

                if($this->project->Accept($invitation['Project_id'], $invitation['Project_role_id'], $invitation['Project_invitation_id'])) {

                    $data = array(
                            "status" => "ok",
                            "status_message" => "Invitation accepted!"
                    );
                }
                // Else, if something went wrong
                else {

                    $data = array(
                            "status" => "error",
                            "status_message" => "Acceptance process failed!"
                    );
                }
            }
            else
            {
                $data = array(
                            "status" => "error",
                            "status_message" => "You are already a member of this project!"
                    );
            }
        }

        // If no status but post

        if($status == false && isset($_POST['accept_btn'])) {

            $data = array(
                "status" => "error",
                "status_message" => "Acceptance process failed!"
            );
        }

        $this->theme->view('project/accept', $data);
    }


    /**
    * Description: Will show the project/leave.php view and
    * catch the Project_id from get.
    * In order to leave a project the logged user need to be
    * a member of the selected project and NOT a General.
		*
		* NOT IN USE
    */

    function Leave($projectID)
    {
        // Add a tracemessage to log
        log_message('debug','#### => Controller Project->Leave');

        // Is user logged in?
        if($this->user->IsLoggedIn()==false)
        {
            // Set errormessage (will be catched in login)
            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');

            // No, redirect
            redirect('account/login');
            return;
        }

        // Is user member in the project?
        if($this->project_member->IsMember($projectID)==false)
        {
            // Set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');

            // Show project start
            redirect("project/view/$projectID");
            return;
        }

        // And not a general
        if($this->project_member->HaveSpecificRoleInCurrentProject('General') != false)
        {
            // Set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'You are the General of this project! Firts give away your General role or choose to delete the entire project.');

            // Show project start
            redirect("project/view/$projectID");
            return;
        }

        // if any project is set; clear variable (project will not exist after this function)
        $this->project->clearCurrentProject();


        $data = array();

        // Make user leave
        if($this->project->Leave($projectID)) {

            $data = array(
                    "status" => "ok",
                    "status_message" => "You have left the project!"
            );
        }
        // Else, if something went wrong
        else {

            $data = array(
                    "status" => "error",
                    "status_message" => "Something went wrong, you are still a member of the project!"
            );
            $this->error->log('Leave project failed.', $_SERVER['REMOTE_ADDR'], 'Project/Leave', 'project_member/Delete', array('Project_id' => $projectID));
        }

       $this->theme->view('project/leave', $data);
    }

    /**
    * Description: Will show the project/kickout.php view and
    * catch the Victim_id(User_id) and the Project_id from get.
    * In order to kick someone out of the project the logged user need to be
    * the General of the selected project
		*
		* NOT IN USE
    */

    function KickOut($victimID, $projectID)
    {
        // Add a tracemessage to log

        log_message('debug','#### => Controller Project->KickOut');

        // Is user logged in?

        if($this->user->IsLoggedIn()==false)
        {
            // Set errormessage (will be catched in login)

            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');

            // No, redirect

            redirect('account/login');
            return;
        }

        // Is logged in user member in the project?

        if($this->project_member->IsMember($projectID)==false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        // Is logged in user General of selected project?

        if ($this->project_member->HaveRoleInCurrentProject('general')==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication You are not an project general.');

            // show project list
            redirect("project/view/$projectID");
            return;
        }

        // Is kick out victim member in the project?

        if($this->project_member->IsVictimMember($victimID, $projectID)==false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'The member you want to kick out is not a member of this project.');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        // Is General schizophrenic?

        if($this->project_member->IsGeneralSchizophrenic($victimID) != false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'You can not kick out yourself!');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        $data = array();

        // Kick out victim

        if($this->project->KickOut($projectID, $victimID)) {

            $data = array(
                    "status" => "ok",
                    "status_message" => "Member is kicked out!"
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

       $data["projectID"] = $projectID;

       $this->theme->view('project/kickout', $data);
    }

    /**
    * Description: Will show the project/switch.php view and
    * catch the Victim_id(User_id) and the Project_id from get.
    * In order to make someone a General of the project the logged user need to be
    * the General himself/herself of the selected project
		*
		* NOT IN USE
    */

    function SwitchGeneral($victimID, $projectID)
    {
        // Add a tracemessage to log

        log_message('debug','#### => Controller Project->SwitchGeneral');

        // Is user logged in?

        if($this->user->IsLoggedIn()==false)
        {
            // Set errormessage (will be catched in login)

            $this->session->set_userdata('errormessage', 'Authentication error! You must be logged in.');

            // No, redirect

            redirect('account/login');
            return;
        }

        // Is logged in user member in the project?

        if($this->project_member->IsMember($projectID)==false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        // Is logged in user General of selected project?

        if ($this->project_member->HaveRoleInCurrentProject('general')==false)
        {
            // set errormessage (will be catched in view)
            $this->session->set_userdata('errormessage', 'Authentication You are not an project general.');

            // show project list
            redirect("project/view/$projectID");
            return;
        }

        // Is kick out victim member in the project?

        if($this->project_member->IsVictimMember($victimID, $projectID)==false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'The member you want to make a general is not a member of this project.');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        // Is General schizophrenic?

        if($this->project_member->IsGeneralSchizophrenic($victimID) != false)
        {
            // Set errormessage (will be catched in view)

            $this->session->set_userdata('errormessage', 'You are already the general of this project');

            // Show project start

            redirect("project/view/$projectID");
            return;
        }

        $data = array();

        // SwitchGeneral

        if($this->project->SwitchGeneral($projectID, $victimID)) {

            $data = array(
                    "status" => "ok",
                    "status_message" => "You are no longer the general of this project!"
            );
        }
        // Else, if something went wrong
        else {

            $data = array(
                    "status" => "error",
                    "status_message" => "Something went wrong, you are still the general of this project!"
            );
            $this->error->log('Switch General failed.', $_SERVER['REMOTE_ADDR'], 'Project/SwitchGeneral');
        }

       $data["projectID"] = $projectID;

       $this->theme->view('project/switchgeneral', $data);
    }

    /**
    * This function is part of the accept invitation validation. It will stop any
    * none invited try
    *
    *@param string $str
    *@return bool
    */

    function invite_check($str)
    {
        if($this->invitation->CheckIfExist("Code", $str) == false) {

            $this->validation->set_message('invite_check', 'This invitation does not exist in our database.');
            return false;
        }

        return true;
    }

    /**
    * Called from project_pre_contant and sets common javascript variables
    * (clientside javascript generated by serverside php)
    */
    function common_variables()
    {
        // add a tracemessage to log
        log_message('debug','#### => Controller Project->common_variables');
        
        // package data
        $data = array(
            'base_url' => $this->config->item('base_url'),
            'site_url' => site_url(),
            'current_project_id' => $this->project->checkCurrentProject()
        );
        
        // no project set? (fallback and will prevent a javascript-error)
        if ($data['current_project_id'] == false)
        {
            echo "// ERROR; no project set";
            return;
        }
        
        // load and output view (NO theme needed)
        $this->load->view($this->theme->GetThemeFolder().'/common/js_variables', $data);    
    }
    
}

/* End of file project_controller.php */
/* Location: ./system/application/controllers/project_controller.php */

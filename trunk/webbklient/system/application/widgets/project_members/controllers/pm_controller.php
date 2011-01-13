<?php

// class can be changed as needed
class pm_controller extends Controller {

	// the class is a regular Codeigniter controller
	// and inherits from CI
	function __construct()
	{
		parent::Controller();
		$this->load->library(array('project_member', 'emailsender', 'invitation'));
    $this->load->library('project_lib', null, 'project', 'widgets');
		$this->load->model(array('project_model'));
	}
	
	// first function to be called if not specified in URL (Codeigniter)
	function index($Pid)
	{
		// Loading
		$this->load->library_widget("pm_library");
		
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
		if($this->project_member->IsMember($Pid)==false)
		{
			// Set errormessage (will be catched in project/view)
			$this->session->set_userdata('errormessage', 'Authentication error! You are not a member of this project.');

			// Redirect to view
			redirect("project/view/$Pid");
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
		$this->project->setCurrentProject($Pid);

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
  
	function sendInvitation() {
	
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
	
  function model_test()
  {
      // load a model inside subfolder 'mdel'
      $this->load->model_widget('testmodel');    
      
      
       // note; the function model_widget
       // is an extended function in Codeigniter
       
      
      // get data from model
      $members = $this->testmodel->getUsers();
      
      // output data from the model
      echo "<div id=\"ajax_template_wrapper\" style=\"padding:15px;\">";
      echo "<h1>AJAX template; Data from model</h1>";
      echo "<p><a href=\"javascript:void(0);\" class=\"small\" onclick=\"ajaxTemplateWidget.loadURL('/some_controller_name');\"><< Back to previous page</a></p>   ";
      echo "<table cellpadding=\"3\">";
      echo "<tr>";
      echo "<td><strong>Firstname</strong></td>";
      echo "<td><strong>Lastname</strong></td>";
      echo "<td><strong>Email</strong></td>";
      echo "</tr>";
      foreach ($members as $row)
      {
            echo "<tr>";
            echo "<td>$row->Firstname</td>";
            echo "<td>$row->Lastname</td>";
            echo "<td>$row->Email</td>";
            echo "</tr>";
      }
      echo "</table></div>";
  }
    
   
  function library_test()
  {
        // load a library
        $this->load->library_widget('Testlib'); 
      
       // note; the function library_widget
       // is an extended function in Codeigniter
      
      
       // output data from the library
       echo $this->testlib->randomFunctionName();
      
  }
  
  function edit_user($userID)
  {
      $widget_name = "ajax_template";  
      
      // load a library
      $this->load->library_widget('Testlib'); 
      
      // note; the function library_widget
      // is an extended function in Codeigniter
      
      
      // get user
      $user = $this->testlib->getUser($userID);
      
      // package some data for the view
      $base_url = $this->config->item('base_url');
      $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            // used in view
            'user' => $user,  
            'query' => $_SERVER['REQUEST_URI']
      );
      
      // load a view for the widget
      // file is located in subfolder 'views'
      // for the widget
      $this->load->view_widget('edit_user', $data); // view is loaded into an iframe (jquery plugin window)
  }
  
  function save_edit_user()  
  {
  
      // output data from post
      echo "<div id=\"ajax_template_wrapper\" style=\"padding:15px;\">";
      echo "<h1>AJAX template; Post data</h1>";
      echo "<p><a href=\"javascript:void(0);\" class=\"small\" onclick=\"ajaxTemplateWidget.loadURL('/some_controller_name');\"><< Back to previous page</a></p>   ";
    
      echo "<table cellpadding=\"3\">";
      echo "<tr>";
      echo "<td><strong>Name</strong></td>";
      echo "<td><strong>Value</strong></td>";
      echo "</tr>";
      foreach ($_POST as $key=>$val)
      {
            echo "<tr>";
            echo "<td>$key</td>";
            echo "<td>$val</td>";
            echo "</tr>";
      }
      echo "</table></div>";
    
      
  }
  
  function partial()
  {
      echo "<div id=\"ajax_template_wrapper\" style=\"padding:15px;\">";
      echo "<h1>AJAX template; setPartialContent</h1>";
      echo "<p><a href=\"javascript:void(0);\" class=\"small\" onclick=\"ajaxTemplateWidget.loadURL('/some_controller_name');\"><< Back to previous page</a></p>   ";   

      echo "<div class=\"ajax_template_partial\" style=\"border:2px solid #777;padding:20px;\">".
            "This area is partial and will be reloaded when clicked.".
            " <a href=\"javascript:void(0);\" onclick=\"ajaxTemplateWidget.loadURLtoPartialTest('/some_controller_name/partialCall');\">Reload partial area</a>".
            "</div>";
            
      echo "<br/><br/>This area will NOT be reloaded.</div>";
  }
	function partialCall()
	{
		echo "<p>This is the new content :)</p>";
	}
  
}

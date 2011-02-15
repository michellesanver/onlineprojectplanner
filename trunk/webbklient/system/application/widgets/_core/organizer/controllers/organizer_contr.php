<?php

// class can be changed as needed
class Organizer_contr extends Controller {
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();
		$this->load->library(array('project_member'));
    }
    
    // first function to be called if not specified in URL (Codeigniter)
    function index($Pid, $pwID)
    {
		// add a tracemessage to log
		log_message('debug','#### => Controller organizer_contr->index');
		
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
		
		$this->load->view_widget("index");
    }
}

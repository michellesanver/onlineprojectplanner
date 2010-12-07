<?php

class Main extends Controller 
{
	
	function __construct()
	{
		parent::Controller();	
		
		$this->load->library('validation');
	}
	
	function index()
	{
        // add a tracemessage to log
        log_message('debug','#### => Controller Main->index');
        
        // add a tracemessage to log
        log_message('debug','Controller Account->RecommendNewUser');
			
		if($this->user->isLoggedIn()) {
			redirect('/project/index');
		} else {
			$this->theme->view('user/login_view');	
		}
	}
	
	function register()
	{
        // add a tracemessage to log
        log_message('debug','#### => Controller Main->register');
        
		$this->theme->view('user/register');	
	}
	
	
}

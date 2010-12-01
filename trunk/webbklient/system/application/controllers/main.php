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
		
			
		if($this->user->isLoggedIn()) {
			redirect('/project_controller/index');
		} else {
			$this->theme->view('user/login_view');	
		}
	}
	
	function register()
	{
		$this->theme->view('user/register');	
	}
	
	
}
<?php

class Main_controller extends Controller 
{
	
	function Main_controller()
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
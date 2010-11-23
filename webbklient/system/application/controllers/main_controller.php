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
		$this->theme->view('user/login_view');		
	}
	function register()
	{
		$this->theme->view('user/register');	
	}
	
	
}
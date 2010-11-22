<?php

class Main_controller extends Controller 
{
	function index()
	{
		$this->theme->view('users/login_view');		
	}

}
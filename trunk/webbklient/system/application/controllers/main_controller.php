<?php

class Main_controller extends Controller 
{
	function index()
	{
		$this->theme->view('user/login_view');		
	}

}
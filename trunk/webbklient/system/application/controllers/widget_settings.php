<?php

class Widget_settings extends Controller {

	function Widget_settings()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->view('welcome_message');
	}
}

/* End of file widget_settings.php */
/* Location: ./system/application/controllers/widget_settings.php */
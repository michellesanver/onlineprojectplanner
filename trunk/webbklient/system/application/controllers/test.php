<?php

class Test extends Controller 
{
	function index()
	{
		$xhtml = "<H1>Test superwiki</H1>";
		
		$xhtml .= "<H3>Test av user</H3>";
		$this->load->model('user_model');
		$xhtml .= $this->user_model->Test();
		
		$data['xhtmlBody'] = $xhtml;
		$this->load->view('test_view', $data);
	}
	
}
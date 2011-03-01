<?php

class Chat extends Controller
{

    private $_widget_name = "chat";

    function __construct()
    {
        parent::Controller();
		$this->load->library(array('User', 'project_member'));
    }

    function index()
    {
        if($this->user->IsLoggedIn()==false)
		{
			echo "You are not authenticated! <a href=\"".site_url('')."\">Login</a>";
			return;
		}
		
		$this->load->library_widget('curl', null, 'curl');
		
		$user = $this->user->getLoggedInUser();
		$projid = $this->project_member->SelectByUserId();
		
		$usrname = $user['Firstname'] . '_' . $user['Lastname'];
		$channel = $projid[0]['Project_id'];
		
		
    
		// package some data for the view
    	$widget_name = $this->_widget_name;
    	$base_url = $this->config->item('base_url');
    	$data = array(
        	'base_url' => $base_url,
        	'widget_url' => site_url("/widget/$widget_name").'/',
        	'widget_base_url' => $base_url."system/application/widgets/$widget_name/");

	 
		$this->curl->create('http://www.pppp.nu:4000/?name='. $usrname .'&channel='. $channel );
		
		$data['site'] = $this->curl->execute();
	
    	$this->load->view_widget('start', $data);
    }

}
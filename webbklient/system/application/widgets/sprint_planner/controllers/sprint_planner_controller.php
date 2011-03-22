<?php

// class can be changed as needed
class Sprint_planner_controller extends Controller {
    
    private $_CI;
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();    
    }
    
    function get_project_members() 
    {
    	 $this->_CI = & get_instance();
    	 $this->_CI->load->library('project_member');
    	 $this->_CI->load->library('Project_lib', null, 'Project');
    	 $projectid = $this->_CI->Project->checkCurrentProject();
    	 $project_members = $this->_CI->project_member->GetMembersByProjectId($projectid);
    	 
    	 return $project_members;
    }
    
    function story_change_done() 
    {
    	$this->load->model_widget('storymodel');
		
		$storyid = $this->input->post('story_id', true);
		$done = $this->input->post('checked', true);
		
		$this->storymodel->changeDone($storyid, $done);	
    }
    
    // first function to be called if not specified in URL (Codeigniter)
    function index($project_widgets_id)
    {
        $widget_name = "sprint_planner";
  		$this->load->model_widget('storymodel');
  		$this->load->model_widget('sprintmodel');
  		
  		$this->_CI = & get_instance();
    	$this->_CI->load->library('project_member');
    	$this->_CI->load->library('Project_lib', null, 'Project');
    	$projectid = $this->_CI->Project->checkCurrentProject();
    	$project_members = $this->_CI->project_member->GetMembersByProjectId($projectid);
    	 
        // package some data for the view
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            'stories' => $this->storymodel->getStories($project_widgets_id),
            'members' => $project_members,
            'sprints' => $this->sprintmodel->getSprints($project_widgets_id),
            'userID' => $this->user->getUserID() // used in a link
        );
        
        // load a view for the widget
        // file is located in subfolder 'views'
        // for the widget
       $this->load->view_widget('start', $data); // view is loaded into an iframe (jquery plugin window)
                
       
       // note; the function view_widget
       // is an extended function in Codeigniter
        
    }   
    
    function add_sprint() {
		$this->load->model_widget('sprintmodel');
		
		$days = $this->input->post('days', true);	
		$name = $this->input->post('sprintname', true);	
		$project_widget_id = $this->input->post('project_widget_id', true);	
		
		if(isset($days) && isset($name) && isset($project_widget_id)) {
			$this->sprintmodel->addSprint($project_widget_id, $days, $name);	
		} else {
			echo("fail");
		}
	}
    
    function delete_sprint() {

		$this->load->model_widget('sprintmodel');
		
		$sprintid = $this->input->post('sprint_id', true);
	
		if(isset($sprintid)) {
			$this->sprintmodel->deleteSprint($sprintid);	
		} else {
			echo("fail");
		}
				
	}
	
	function add_story() {

		
		$this->load->model_widget('storymodel');
		
		$story = $this->input->post('story', true);
		$assignee = $this->input->post('assignee', true);
		$description = $this->input->post('description', true);
		$points = $this->input->post('points', true);
		$project_widget_id = $this->input->post('project_widget_id', true);
		
		if(isset($story) && isset($assignee) && isset($description) && isset($points) && isset($project_widget_id)) {
			$this->storymodel->addStory($project_widget_id, $story, $assignee, $description, $points);	
		} else {
			echo("fail");
		}
				
	}
	
	
	function add_sprint_to_story() {
		$this->load->model_widget('storymodel');
		
		$storyid = $this->input->post('story_id', true);
		$sprintid = $this->input->post('sprint_id', true);
		
		if($sprintid == 'delete') {
			$sprintid = null;
		}
		$this->storymodel->addSprintToStory($storyid, $sprintid);	
				
	}
	
	function edit_print() {
		
		$this->load->model_widget('storymodel');
		
		$days = $this->input->post('days', true);	
		$name = $this->input->post('sprintname', true);	
		$sprintid = $this->input->post('sprint_id', true);
		
		if(isset($sprintid) && isset($days) && isset($name)) {
			$this->sprintmodel->editSprint($sprintid, $days, $name);	
		} else {
			echo("fail");
		}
				
	}
	
	function edit_story() {

		
		$this->load->model_widget('storymodel');
		
		$storyid = $this->input->post('story_id', true);
		$story = $this->input->post('story', true);
		$assignee = $this->input->post('assignee', true);
		$description = $this->input->post('description', true);
		$points = $this->input->post('points', true);
		$project_widget_id = $this->input->post('project_widget_id', true);
		
		if(isset($storyid) && isset($story) && isset($assignee) && isset($description) && isset($points) && isset($project_widget_id)) {
			$this->storymodel->editStory($storyid, $story, $assignee, $description, $points);	
		} else {
			echo("fail");
		}
				
	}
	
	function delete_story() {

		$this->load->model_widget('storymodel');
		
		$storyid = $this->input->post('story_id', true);
	
		if(isset($storyid)) {
			$this->storymodel->deleteStory($storyid);	
		} else {
			echo("fail");
		}
				
	}
  
	function getAllStories($project_widget_id)
	{
		$this->load->model_widget('storymodel');
		$stories = json_encode($this->storymodel->getStories($project_widget_id));
		die($stories);  	
	}
	
	function get_all_sprints($project_widget_id)
	{
		$this->load->model_widget('sprintmodel');
		$sprints = json_encode($this->sprintmodel->getSprints($project_widget_id));
		die($sprints);  	
	}
	
	function get_stories_in_sprint($sprint_id, $project_widget_id)
	{
		$this->load->model_widget('storymodel');
		$stories = $this->storymodel->getStoriesInSprint($sprint_id);
		$allstories = $this->storymodel->getStories($project_widget_id);
		
		$array = array();
		$array['stories'] = $stories;
		$array['allstories'] = $allstories;
		
		die(json_encode($array));  	
	}
	
	function get_days($project_widget_id)
	{
		$this->load->library("settings_provider");
		$days = $this->settings_provider->getSettingValue("sprint_planner", 6, $project_widget_id);
		
		if($days == 0) {
			$days = 15;
		}
		
		echo($days);
		die();
	
	}
	
	function get_points($story_id, $days)
	{
		$this->load->model_widget('storymodel');
		$pointsarray = array();
		
		for($index = 1; $index <= $days; $index++) {
			$pointsarray[$index] = $this->storymodel->getPointsForStoryAndDay($story_id, $index);
		}
		
		$allpoints = json_encode($pointsarray);
		die($allpoints);  
	}
  
	function getStory($storyid)
	{
		$this->load->model_widget('storymodel');
		$stories = json_encode($this->storymodel->getStory($storyid));
		die($stories);  	
	}
	
	function save_points()
	{
		$this->load->model_widget('storymodel');
		
		$day = $this->input->post('day', true);
		$story_id = $this->input->post('story_id', true);
		$points = $this->input->post('daypoints', true);
				
		if($story_id && $day && $points) {
			$this->storymodel->savePoints($story_id, $day, $points);	
		} else {
			echo("fail");
		}
	}
	
	function get_all_points($project_widget_id)
	{
		$params = array('project_widget_id' => $project_widget_id);
		$this->load->library_widget('sprintplanner', $params);
		
		$this->load->library("settings_provider");
		$numberdays = $this->settings_provider->getSettingValue("sprint_planner", 6, $project_widget_id);
		
		if($numberdays == 0) {
			$numberdays = 15;
		}
		
		$allpoints = $this->sprintplanner->getAllPoints();
		$days = $this->sprintplanner->getPointsPerDay($numberdays, $allpoints, 'chart1');
		
		die(json_encode($days));
		
	}
  
  	function get_all_sprint_points($project_widget_id, $sprint_id)
	{
		$params = array('project_widget_id' => $project_widget_id);
		$this->load->library_widget('sprintplanner', $params);
		$this->load->model_widget('storymodel');
		
		$allpoints = $this->storymodel->getPointsInSprint($sprint_id);
		
		if(empty($allpoints)) {
			$days = 0;
		} else {
			$days = $this->sprintplanner->getPointsPerDay($allpoints[0]->Days, $allpoints, 'sprintchart_' . $sprint_id);
		}
		
		die(json_encode($days));
		
	}
  
}

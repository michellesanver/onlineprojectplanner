<?php 

class Sprintplanner {
	
	private $_stories;
	private $_CI;
	private $_storyIds;
	private $_totalPoints;
	private $_instance_id;
	private $_hasPoints;
	private $_hasStories;
	
	function __construct($params)
	{
		$this->_hasPoints = true;
		$this->_hasStories = true;
		$this->_instance_id = $params['project_widget_id'];
		$this->_project_id = $params;
		$this->_CI = & get_instance();
		$this->_CI->load->model_widget('storymodel');
		$this->_CI->load->library('project_member');
		
		$this->_stories = $this->_CI->storymodel->getStories($this->_instance_id);
		$this->_init();
	}
	
	private function _init()
    {
       	$array = $this->_CI->storymodel->getStories($this->_instance_id);
    	
    	if(!is_null($array)) {
	    	//Get all id's
	    	$idarray = array();
	    	$totalpoints = 0;
	    	
	    	foreach($array as $story) {
	    		$idarray[] = $story->Stories_id;
	    		$totalpoints += $story->Total_points;
	    	}
	    	
	    	$this->_storyIds = $idarray;
	    	$this->_totalPoints = $totalpoints;
    	} else {
    		$this->_hasStories = false;
    	}
    	
    } 
    
    function getStoryIds()
    {
    	return $this->_storyIds;
    }
    
    function getTotalPoints()
    {
    	return $this->_totalPoints;
    }
    
    function getProjectMembers()
    {
    	return $this->_CI->project_member->GetMembersByProjectId($this->_project_id);
    }
    
    function getAllPoints() 
    {
    	$allpoints = $this->_CI->storymodel->getAllPoints($this->_storyIds);
    	if(is_null($allpoints)) {
    		$this->_hasPoints = false;
    	}
    	
    	return $allpoints;
    }
    
    function getPointsPerDay($totaldays, $allpoints, $chartname)
    {
    
    	if(is_null($allpoints)) {
    		$this->_hasPoints = false;
    	}
    	
    	$days = array();
    	$pointsarray = array();
		
		if($this->_hasPoints == false || $this->_hasStories == false) {
    		$days[0] = 0;
    		return $days;
    	}
    	
    	foreach($allpoints as $var => $points) {
    		$day = $points->Day;
    		if(array_key_exists($day, $days)) {
    			$days[$day] += $points->Points_done;
    		} else {
    			$days[$day] = $points->Points_done;
    		}

    	}
    	
    	for($day = 1; $day <= $totaldays; $day++) {
    		if(!array_key_exists($day, $days)) {
    			$days[$day] = 0;
    		}
    	}
    	
    	
    	$totalpoints = 0;
	    $storiesarray = array();
	    
    	foreach($allpoints as $point) {
    		if(!in_array($point->Stories_id, $storiesarray)) {
    			$storiesarray[] = $point->Stories_id;
    			$totalpoints += $point->Total_points;
    		}
    		
    	}
    	
    	$this->_totalPoints = $totalpoints;
    	
    	$totalcounter = $totalpoints;
    	
    	for($day = 1; $day <= $totaldays; $day++) {
    		$points = $days[$day];
    		$totalcounter -= $points;
    		$days[$day] = $totalcounter;
    		
    	}
    	
		$days[0] = $this->_totalPoints;
    	ksort($days);
    	
    	$pointsarray['points'] = $days;
		$pointsarray['days'] = $totaldays+1;
		$pointsarray['chartid'] = $chartname;
    	return $pointsarray;    	
    }
	
}
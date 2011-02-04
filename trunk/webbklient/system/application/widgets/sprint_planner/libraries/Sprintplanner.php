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
		$this->_CI = & get_instance();
		$this->_CI->load->model_widget('storymodel');
		
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
    
    function getAllPoints() 
    {
    	$allpoints = $this->_CI->storymodel->getAllPoints($this->_storyIds);
    	if(is_null($allpoints)) {
    		$this->_hasPoints = false;
    	}
    	
    	return $allpoints;
    }
    
    function getPointsPerDay($totaldays)
    {
    	$days = array();
    	$allpoints = $this->getAllPoints();
		
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
    	
    	$totalcounter = $this->_totalPoints;
    	
    	for($day = 1; $day <= $totaldays; $day++) {
    		$points = $days[$day];
    		$totalcounter -= $points;
    		$days[$day] = $totalcounter;
    		
    	}
    	
		$days[0] = $this->_totalPoints;
    	ksort($days);
    	return $days;    	
    }
	
}
<?php

class Storymodel extends Model {

    function __construct()
    {
        parent::Model();
    }
    
    function addStory($project_widgets_id, $story, $assignee, $description, $points)
    {
	    $this->db->trans_begin();  

        // insert row
        $this->db->insert('WI_Sprintplanner_Stories', array('Instance_id' => $project_widgets_id, 'Assignee' => $assignee, 'Name' => $story, 'Description' => $description, 'Total_points' => $points));
     
        // nothing changed?
        if ( $this->db->affected_rows() == 0 )
        {   
            // rollbak db and return false 
            $this->db->trans_rollback();
            return false;
        } else {
        	 // else; all ok! commit transaction and return true
	        $this->db->trans_commit(); 
	        return true;
        }
        
       
    }
    
    function getAllPoints($storyids) 
    {
    	$this->db->where_in('Story_id', $storyids);
    	$query = $this->db->get("WI_Sprintplanner_Points");
    	
    	if($query && $query->num_rows() > 0 ) {
        	return $query->result();
        } else {
        	return null;
        }
    }
    
    function savePoints($storyid, $day, $points)
    {
    	$query = $this->db->get_where("WI_Sprintplanner_Points", array('Story_id' => $storyid, 'Day' => $day) );
        
        if($query && $query->num_rows() > 0 ) {
        	$this->db->where(array('Story_id' => $storyid, 'Day' => $day));
			$this->db->update('WI_Sprintplanner_Points', array('Points_done' => $points));  
        } else {
         	$this->db->trans_begin();
        	$this->db->insert('WI_Sprintplanner_Points', array('Day' => $day, 'Story_id' => $storyid, 'Points_done' => $points));
        	if ( $this->db->affected_rows() == 0 )
	        {   
	            // rollbak db and return false 
	            $this->db->trans_rollback();
	            return false;
	        } else {
	        	 // else; all ok! commit transaction and return true
		        $this->db->trans_commit(); 
		        return true;
	        }
        }
    }
    
    function editStory($storyid, $story, $assignee, $description, $points)
    {
	    $this->db->where(array('Stories_id' => $storyid) );
        $this->db->update('WI_Sprintplanner_Stories', array('Assignee' => $assignee, 'Name' => $story, 'Description' => $description, 'Total_points' => $points));       
    }
    
    function getPointsForStoryAndDay($storyid, $day) {
    	$query = $this->db->get_where("WI_Sprintplanner_Points", array('Story_id' => $storyid, 'Day' => $day) );
        
        if($query && $query->num_rows() > 0 ) {
        	$result = $query->result();
        	return $result[0];
        } else {
        	$result = array(
        		'Day' => $day,
        		'Points_done' => 0,
        		'Story_id' => $storyid
        	);
        	return $result;
        }

    }
    
    function addSprintToStory($storyid, $sprintid)
    {
    	$this->db->where(array('Stories_id' => $storyid) );
        $this->db->update('WI_Sprintplanner_Stories', array('Sprint_id' => $sprintid));   
    }
    
    function deleteStory($storyid)
    {
	    // start transaction (function will FAIL if transaction is not used)
        $this->db->trans_begin();  
        

        $result = $this->db->delete('WI_Sprintplanner_Stories', array('Stories_id' => $storyid) );

        
        // nothing changed?
        if ($result == false )
        {   
            // rollbak db and return false
            $this->db->trans_rollback();
            return false;
        }
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit(); 
        return true;    
    }
    
    function getStories($project_widgets_id)
    {
    
    	$this->db->select('*');
    	$this->db->from('WI_Sprintplanner_Stories');
    	$this->db->where('WI_Sprintplanner_Stories.Instance_id', $project_widgets_id);
    	$query = $this->db->get();
    	        
        if($query && $query->num_rows() > 0 ) {
        	return $query->result();
        } else {
        	return null;
        }
    }  
   
    function getStoriesInSprint($sprint_id)
    {
        
        $this->db->select('*');
    	$this->db->from('WI_Sprintplanner_Stories');
    	$this->db->where('WI_Sprintplanner_Stories.Sprint_id', $sprint_id);
    	$this->db->join('WI_Sprintplanner_Sprints', 'WI_Sprintplanner_Stories.Sprint_id = WI_Sprintplanner_Sprints.Sprint_id');
          
        $query = $this->db->get();
        
        if($query && $query->num_rows() > 0 ) {
        	return $query->result();
        } else {
        	return null;
        }
    }
    
    function getPointsInSprint($sprint_id)
    {
        
        $this->db->select('*');
    	$this->db->from('WI_Sprintplanner_Stories');
    	$this->db->where('WI_Sprintplanner_Stories.Sprint_id', $sprint_id);
    	$this->db->join('WI_Sprintplanner_Sprints', 'WI_Sprintplanner_Stories.Sprint_id = WI_Sprintplanner_Sprints.Sprint_id');
    	$this->db->join('WI_Sprintplanner_Points', 'WI_Sprintplanner_Stories.Stories_id = WI_Sprintplanner_Points.Story_id');
          
        $query = $this->db->get();
      
        if($query && $query->num_rows() > 0 ) {
        	return $query->result();
        } else {
        	return null;
        }
        
    }
    
    function getStory($storyid)
    {
    	$query = $this->db->get_where("WI_Sprintplanner_Stories", array('Stories_id' => $storyid) );
        
        if($query && $query->num_rows() > 0 ) {
        	$result =  $query->result();
        	return $result[0];
        } else {
        	return null;
        }
    }  
}
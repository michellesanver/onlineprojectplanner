<?php

class Sprintmodel extends Model {

	function __construct()
	{
		parent::Model();
	}
	
	/**
	 * Adds a sprint to the project.
	 */
	function addSprint($project_widgets_id, $days, $name)
	{
		// begin transaction
		$this->db->trans_begin();

		// insert row
		$this->db->insert('WI_Sprintplanner_Sprints', 
			array(
				'Instance_id' => $project_widgets_id, 
				'Days' => $days, 
				'Sprint_name' => $name
				)
		);

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
	
	function editSprint($sprintid, $days, $name)
    {
    	$this->db->where(array('Sprint_id' => $sprintid) );
        $this->db->update('WI_Sprintplanner_Sprints', array('Days' => $days, 'Sprint_name' => $name));   
    }
    
    function deleteSprint($sprintid)
    {
	    // start transaction (function will FAIL if transaction is not used)
        $this->db->trans_begin();  
        

        $result = $this->db->delete('WI_Sprintplanner_Sprints', array('Sprint_id' => $sprintid) );

        
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
    
    function getSprints($project_widgets_id)
    {
    	$query = $this->db->get_where("WI_Sprintplanner_Sprints", array('Instance_id' => $project_widgets_id) );
        
        if($query && $query->num_rows() > 0 ) {
        	return $query->result();
        } else {
        	return null;
        }
    }
	
}
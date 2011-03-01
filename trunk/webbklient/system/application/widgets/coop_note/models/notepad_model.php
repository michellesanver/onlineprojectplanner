<?php

class Notepad_model extends Model {
	
	private $_table = "WI_Notepad_Pads";
	
    function __construct()
    {
        parent::Model();
    }
    
    /**
     * returns the notepad rows in the database
     * 
     * @param number $projectId
     * @return Array
     */
    function getAllProjectPads($projectId){
    	$this->db->select('*');
    	$this->db->from($this->_table);
    	$this->db->where( array( "Project_Id" => $projectId ) );
    	$query = $this->db->get();  
  
         // any result?
         if ($query && $query->num_rows() > 0)
         {
             // return results
            return $query->result_array();            
         }
         else
         {
            return array();
         }
    }
    
    /**
     * Saves the data to the the database
     * 
     * @param array $save
     * @return bool
     */
    function Save($save){
		$this->db->insert($this->_table, $save);
		return $this->db->insert_id();
    }
    
    /**
     * Updates the data to the the database
     * 
     * @param array $update
     * @return bool
     */
    function Update($update){
		$this->db->where('Pads_Id', $update['Pads_Id']);
		return $this->db->update($this->_table, $update);
    }
    
    /**
     * Removes a row in the database
     * 
     * @param number $padId
     * @return bool
     */
    function Delete($padId){
		$this->db->where('Pads_Id', $padId);
		return $this->db->delete($this->_table);
    }
}
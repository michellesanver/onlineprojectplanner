<?php

class Testmodel extends Model {

    function __construct()
    {
        parent::Model();
    }
    
    function getUsers()
    {
        // uses standard CI active record to fetch data
        
        $this->db->select('Firstname, Lastname, Email');
        $query = $this->db->get("User");
        
        if($query && $query->num_rows() > 0 )
            return $query->result();
        else
            return null;
    }
    
}
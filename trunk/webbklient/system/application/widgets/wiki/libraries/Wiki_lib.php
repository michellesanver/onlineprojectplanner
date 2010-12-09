<?php
 
Class Wiki_lib
{
    private $_CI = null;
    private $_Current_Project_id = "";
    
    function __construct()
    {
        // fetch CI instance and model for library
        $this->_CI = & get_instance();
        $this->_CI->load->model_widget('wiki_model', 'Wiki_model'); 
        
        // fetch current project id
        $this->_CI->load->library('Project_lib', null, 'Project');
        $this->_Current_Project_id = $this->_CI->Project->checkCurrentProject();
    }
    
    /**
    * Fetch data for creating a menu.
    * Will return an empty array of
    * none found.
    * 
    * @return array
    */
    function GetMenuTitles()
    {
        return $this->_CI->Wiki_model->FetchAllMenuTitles($this->_Current_Project_id); 
    }
    
    /**
    * Fetch all new pages for startpage
    * Will return an empty array of
    * none found.
    * 
    * @return array
    */
    function GetNewPages()
    {
         return $this->_CI->Wiki_model->FetchAllNewPages($this->_Current_Project_id);   
    }
    
    /**
    * Fetch all updated pages for startpage
    * Will return an empty array of
    * none found.
    * 
    * @return array
    */
    function GetLastUpdatedPages()
    {
        return $this->_CI->Wiki_model->FetchAllUpdatedPages($this->_Current_Project_id);  
    }
    
}
  
?>

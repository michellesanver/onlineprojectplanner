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
    
    
    /**
    * Fetch a page by id from db.
    * Will return empty array if not found.
    * 
    * @param int $id
    * @return mixed
    */
    function GetPage($id)
    {
        return $this->_CI->Wiki_model->FetchPage($id);     
    }
    
    
    /**
    * Fetch history for a page by id
    * Will return false if no history
    * 
    * @param int $id
    * @return mixed  
    */
    function GetHistory($id)
    {
        return $this->_CI->Wiki_model->FetchHistory($id);    
    }
    
    /**
    * Fetch a page from history to display it
    * 
    * @param int $id
    * @return mixed  
    */
    function GetHistoryPage($id)
    {
        return $this->_CI->Wiki_model->FetchHistoryPage($id);    
    }
    
}
  
?>

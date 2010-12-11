<?php
 
Class Wiki_lib
{
    private $_CI = null;
    private $_Current_Project_id = "";
    
    private $_changelog_filename = "../changelog.xml"; // relative to this file
    
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
    * Will return the changelog.xml
    * as simplexml or false if not found.
    * 
    * @return mixed
    */
    function GetChangelog()
    {
        $dir = dirname(__FILE__);    
        if ( file_exists($dir.'/'.$this->_changelog_filename) )
        {
            // read file and return    
            return @simplexml_load_file($dir.'/'.$this->_changelog_filename);
        }
        else
        {
            // file was not found
            return false;
        }
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
        // get results from db
        $result = $this->_CI->Wiki_model->FetchAllMenuTitles($this->_Current_Project_id); 
        
        // sort children
        $unset_wiki_page_id = array();
        foreach ($result as $row)
        {
            
            // any parent set?
            if ( empty($row->Parent_wiki_page_id) == false )
            {
                // find correct parent
                foreach($result as $row2)
                {
                
                    // does id match?
                    if ( empty($row2)==false && (int)$row->Parent_wiki_page_id==(int)$row2->Wiki_page_id)
                    {
                        // add children array if not found
                        if ( isset($row2->children)==false)
                        {
                            $row2->children = array();    
                        }
                        
                        // save to parent
                        array_push($row2->children, $row);        
                        
                        // save id to be excluded
                        array_push($unset_wiki_page_id, $row->Wiki_page_id);
                    }
                    
                }
            }
         
        }
      
        $final_result = array();
      
        // create a new array and exclude id's that have been moved to a parent
        $len = count($result);
        foreach ($result as $row)     
        {
              if ( in_array($row->Wiki_page_id, $unset_wiki_page_id) == false )
              {
                array_push($final_result, $row);    
              }
        }  
           
        // return the sorted result
        return $final_result;
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
        // get page
        $page = $this->_CI->Wiki_model->FetchPage($id);     
        
        // get tags for page
        $page->Tags = $this->_CI->Wiki_model->FetchPageTags($id); 
        
        // mash up tags for a string (edit page)
        if ( empty($page->Tags) == false)
        {
            $page->Tags_string = "";
            
            $len = count($page->Tags);
            for($n=0; $n<$len; $n++)
            {
                $page->Tags_string .= $page->Tags[$n]->Tag;    
                if ( $n+1<$len)
                {
                    $page->Tags_string .= ", ";
                }
            }
        }
        else
        {
            $page->Tags_string = "";    
        }
        
        // return data
        return $page;
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
        // get page 
        $page = $this->_CI->Wiki_model->FetchHistoryPage($id);    
        
        // get tags for page
        $page->Tags = $this->_CI->Wiki_model->FetchPageTags($id); 
        
        // mash up tags for a string (edit page)
        if ( empty($page->Tags) == false)
        {
            $page->Tags_string = implode($page->Tags, ', ');
        }
        else
        {
            $page->Tags_string = "";    
        }
        
        // return data
        return $page;
    }
    
    /**
    * Fetch all titles that doesn't have
    * any children to use in a select-list.
    * 
    * @return mixed
    */
    function GetTitlesWithoutChildren()
    {
        return $this->_CI->Wiki_model->FetchTitlesWithoutChildren();   
    }
    
}
  
?>

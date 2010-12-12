<?php
 
Class Wiki_lib
{
    private $_CI = null;
    private $_Current_Project_id = "";
    private $_changelog_filename = "../changelog.xml"; // relative to this file
    private $_last_error = "";
    
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
    * This function will return the last error
    * this class has set.
    */
    function GetLastError()
    {
        // save error, clear message and return

        $returnStr = $this->_last_error;
        $this->_last_error = "";
        return $returnStr;
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
        
        // was page found?
        if ($page === false)
        {
            // no, quit
            return false;
        }
        
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
    
    /**
    * Save a new wikipage; will return false or new wiki_page_id
    * 
    * @param string $title
    * @param string $text
    * @param string $tags
    * @param int $parent
    * @param int $order
    * @return mixed
    */
    function SaveNewPage($title, $text, $tags, $parent, $order)
    {
        // apply business rules
        $author = $this->_CI->user->getUserID();
        $project = $this->_Current_Project_id;
        $order = (empty($order) ? 0 : (int)$order); // default order: 0
        $parent = (empty($parent) ? '' : (int)$parent); // default parent: none
        $version = 1;
        
        // prepare tags
        if (empty($tags)==false)
        {
            // more than one tag?
            if (preg_match('/,/', $tags))
            {
                // split tags based on comma (kill spaces also)
                $tags = explode(',', preg_replace('/\s/','', $tags));    
            }
            else
            {
                // manually setup only one tag (kill spaces also) 
                $tags = array( preg_replace('/\s/','', $tags) );
            }
        }
        else
        {
            // no tags to save
            $tags = array();
        }
        
        // save page in model
        $new_wiki_page_id = $this->_CI->Wiki_model->SaveNewWikiPage($title, $text, $parent, $order, $version, $author, $project, $tags);
        
        // any error?
        if ( $new_wiki_page_id != false )
        {
            // no, return new id
            return $new_wiki_page_id;
        }
        else
        {
            // set message and return false
            $this->_last_error = "Database error - unable to save new page";
            return false;
        }
    }
    
    /**
    * This will delete a page and all history 
    * 
    * @return bool
    */
    function DeletePage($id)
    {
        // delete with model
        $result = $this->_CI->Wiki_model->DeletePage($id);
        
        // what was the result?
        if ( $result == false )
        {
            // set message
            $this->_last_error = "Database error - unable to delete page";
            return false;
        }
        else
        {
            // run a search/update if any child is without parent
            $this->_CI->Wiki_model->UpdateNoParent();
            
            // all ok!
            return true;
        }
        
    }
    
    /**
    * Will search in the wiki for a word (full-text).
    * Returns false or a db-result
    * 
    * @param string $word
    * @return mixed
    */
    function SearchByWord($word)
    {
        $word = strtolower($word);
        return $this->_CI->Wiki_model->SearchByWord($word);
    }
    
    /**
    * Will search in the wiki for a tag
    * Returns false or a db-result
    * 
    * @param string $tag
    * @return mixed
    */
    function SearchByTag($tag)
    {
        $tag = strtolower($tag);
        return $this->_CI->Wiki_model->SearchByTag($tag);   
    }
    
    /**
    * Update a page, tags, move current version to history
    * 
    * @param int $Wiki_page_id
    * @param string $title
    * @param string $text
    * @param string $tags
    * @param string $parent
    * @param string $order
    * @return bool
    */
    function UpdatePage($Wiki_page_id, $title, $text, $tags, $parent, $order)
    {
        $tags = strtolower($tags);    
        return $this->_CI->Wiki_model->UpdatePageAndTags($Wiki_page_id, $title, $text, $tags, $parent, $order);
    }
    
}
  
?>


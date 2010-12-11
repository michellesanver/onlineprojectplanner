<?php
  
Class Wiki_model extends Model
{
    private $_table_pages = "WI_Wiki_Pages";
    private $_table_history = "WI_Wiki_Pages_History";
    private $_table_tags = "WI_Wiki_Tags";
    private $_table_users = "User"; 

    function FetchAllMenuTitles($projectID)
    {
        // fetch titles for menu
        $this->db->select('Wiki_page_id, Parent_wiki_page_id, Title');
        $this->db->order_by('Order ASC');
        $this->db->where(array('Project_id'=> $projectID));
        $query = $this->db->get($this->_table_pages);  
  
         // any result?
         if ($query && $query->num_rows() > 0)
         {
             // return results
            return $query->result();            
         }
         else
         {
            return array();
         }
    }

    
    function FetchAllNewPages($projectID)
    {
         $table1 = $this->_table_pages;
         $table2 = $this->_table_users;
         
        // fetch titles for menu
        $this->db->select("$table1.Wiki_page_id, $table1.Title, $table1.Created, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->order_by("Created DESC");
        $this->db->where( array("Project_id" => $projectID) );
        $this->db->limit(3);
        $query = $this->db->get();        
     
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->result();
         }
         else
         {
            return array();
         }  
    }

    
    function FetchAllUpdatedPages($projectID)
    {
        
         $table1 = $this->_table_pages;
         $table2 = $this->_table_users;
         
        // fetch titles for menu
        /*$this->db->select("$table1.Wiki_page_id, $table1.Title, $table1.Updated, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->order_by("$table1.Updated DESC");
        $this->db->where( array("Project_id" => $projectID) );
        $this->db->limit(3);
        $query = $this->db->get();        */

        $projectID = $this->db->escape($projectID);
        
        $sql = "SELECT `$table1`.`Wiki_page_id`, `$table1`.`Title`, `$table1`.`Updated`, `$table2`.`Firstname`, `$table2`.`Lastname` FROM (`$table1`) JOIN `$table2` ON `$table1`.`User_id` = `$table2`.`User_id` WHERE `Project_id` = '10' AND `$table1`.Updated IS NOT NULL ORDER BY `$table1`.`Updated` DESC LIMIT 3";
        $query = $this->db->query($sql);
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->result();
         }
         else
         {
            return array();
         }    
    }
    
    
    function FetchPage($Wiki_page_id)
    {
        // use common function set table page
        return $this->_commonFetchPage($this->_table_pages, array('Wiki_page_id'=>$Wiki_page_id));
    }
    
    
    function FetchHistory($Wiki_page_id)
    {
        // get table names
        $table1 = $this->_table_history;
        $table2 = $this->_table_users;
        
        // setup query with join to get author name 
        $this->db->select("$table1.Wiki_page_history_id, $table1.Title, $table1.Version, $table1.Created, $table1.Updated, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->where(array('Wiki_page_id'=>$Wiki_page_id)); 
        $this->db->order_by("$table1.Order DESC");
        $query = $this->db->get();
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->result();
         }
         else
         {
            return array();
         }     
    }
    
    function FetchHistoryPage($Wiki_page_history_id)
    {
        // use common function set table history
        return $this->_commonFetchPage($this->_table_history, array('Wiki_page_history_id'=>$Wiki_page_history_id));
    }
    
    private function _commonFetchPage($table1, $where)
    {
        // get table names
        $table2 = $this->_table_users;
        
        // setup query with join to get author name
        $this->db->select("$table1.*, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->where($where); 
        $this->db->limit(1);
        $query = $this->db->get();
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->row(0);
         }
         else
         {
            return false;
         }
    }
    
    function FetchPageTags($Wiki_page_id)
    {
        // get tags for page
        $query = $this->db->get_where($this->_table_tags, array('Wiki_page_id'=>$Wiki_page_id));
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->result();
         }
         else
         {
             // else return empty array
            return array();
         }
    }
    
    function FetchTitlesWithoutChildren()
    {
        // manual query since "is null" is not supported by active record
        $table = $this->_table_pages;
        $sql = "SELECT `Wiki_page_id`, `Title` FROM (`$table`) WHERE `Parent_wiki_page_id` IS NULL ORDER BY `Title`;";
        
        $query = $this->db->query($sql);
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            return $query->result();
         }
         else
         {
             // else return empty array
            return array();
         }
    }
}  
  
?>

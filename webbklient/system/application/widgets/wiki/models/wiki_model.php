<?php
  
Class Wiki_model extends Model
{
    private $_table_pages = "WI_Wiki_Pages";
    private $_table_history = "WI_Wiki_Pages_History";
    private $_table_tags = "WI_Wiki_Tags";
    private $_table_tags_history = "WI_Wiki_Tags_History";
    private $_table_users = "User"; 

    function FetchAllMenuTitles($projectID, $instance_id)
    {
        // fetch titles for menu
        $this->db->select('Wiki_page_id, Parent_wiki_page_id, Title');
        $this->db->order_by('Order ASC');
        $this->db->where( array( 'Project_id' => $projectID, 'Instance_id' => $instance_id ) );
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

    
    function FetchAllNewPages($projectID, $instance_id)
    {
         $table1 = $this->_table_pages;
         $table2 = $this->_table_users;
         
        // fetch titles for menu
        $this->db->select("$table1.Wiki_page_id, $table1.Title, $table1.Created, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->order_by("Created DESC");
        $this->db->where( array( "Project_id" => $projectID, 'Instance_id' => $instance_id ) );
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
        
        $sql = "SELECT `$table1`.`Wiki_page_id`, `$table1`.`Title`, `$table1`.`Updated`, `$table2`.`Firstname`, `$table2`.`Lastname` FROM (`$table1`) JOIN `$table2` ON `$table1`.`User_id` = `$table2`.`User_id` WHERE `Project_id` = $projectID AND `$table1`.Updated IS NOT NULL ORDER BY `$table1`.`Updated` DESC LIMIT 3";
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
    
    
    function FetchPage($Wiki_page_id, $instance_id)
    {
        // use common function set table page
        return $this->_commonFetchPage($this->_table_pages, array('Wiki_page_id'=>$Wiki_page_id, 'Instance_id'=>$instance_id));
    }
    
    
    function FetchHistory($Wiki_page_id, $instance_id)
    {
        // get table names
        $table1 = $this->_table_history;
        $table2 = $this->_table_users;
        
        // setup query with join to get author name 
        $this->db->select("$table1.Wiki_page_history_id, $table1.Title, $table1.Version, $table1.Created, $table1.Updated, $table2.Firstname, $table2.Lastname");
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->where(array('Wiki_page_id'=>$Wiki_page_id, 'Instance_id'=>$instance_id)); 
        $this->db->order_by("$table1.Version DESC");
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
    
    function FetchHistoryPage($Wiki_page_history_id, $instance_id)
    {
        // use common function set table history
        return $this->_commonFetchPage($this->_table_history, array('Wiki_page_history_id'=>$Wiki_page_history_id, 'Instance_id'=>$instance_id));
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
    
    function FetchPageTagsHistory($Wiki_page_history_id)
    {
        // use common function to fetch
        return $this->_commonFetchTags($this->_table_tags_history, array('Wiki_page_history_id'=>$Wiki_page_history_id));
    }
    
    function FetchPageTags($Wiki_page_id)
    {
        // use common function to fetch
        return $this->_commonFetchTags($this->_table_tags, array('Wiki_page_id'=>$Wiki_page_id));
    }
    
    private function _commonFetchTags($table, $where)
    {
        // get tags for page
        $query = $this->db->get_where($table, $where);
        
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
    
    function FetchTitlesWithoutChildren($project_id, $instance_id)
    {
        // manual query since "is null" is not supported by active record
        $table = $this->_table_pages;
        $sql = "SELECT `Wiki_page_id`, `Title` FROM (`$table`) WHERE `Parent_wiki_page_id` IS NULL AND `Project_id` = '$project_id' AND `Instance_id` = '$instance_id' ORDER BY `Title`;";
        
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
    
    function SaveNewWikiPage($instance_id, $title, $text,  $parent, $order, $version, $author, $project, $tags)
    {
        // start a transaction; all or nothing
        $this->db->trans_begin();
        
        // package data
        $data = array(
            'Instance_id' => $instance_id,
            'Project_id' => $project,
            'User_id' => $author,
            'Title' => $title,
            'Text' => $text,
            'Order' => $order,
            'Version' => $version
        );
        
        // has parent?
        if (empty($parent)==false)
        {
            $data['Parent_wiki_page_id'] = $parent;    
        }
        
        // save page
        $res = $this->db->insert($this->_table_pages, $data);
        
        // check result
        if ($res == false)
        {
            // something went wrong
            // rollback transaction and return false
            $this->db->trans_rollback();
            return false;  
        }

        // fetch new id
        $new_wiki_page_id = $this->db->insert_id();
            
        // save tags
        foreach($tags as $tag)
        {
            // package data
            $data = array(
                'Wiki_page_id' => $new_wiki_page_id,
                'Tag' => $tag
            ); 
         
            // save current tag
            $res = $this->db->insert($this->_table_tags, $data);
            
            // check result
            if ($res == false)
            {
                // something went wrong
                // rollback transaction and return false
                $this->db->trans_rollback();
                return false;  
            }
        }
        
        // else; all ok! commit transaction and return new id
        $this->db->trans_commit();
        return $new_wiki_page_id;
    }
    
    function DeletePage($Wiki_page_id)
    {
        $this->db->trans_begin(); 
        
        // deeeeelete!
        $res = $this->db->delete($this->_table_pages, array('Wiki_page_id' => $Wiki_page_id));
 
        // was row deleted?
        if ( $res == false )
        {
            $this->db->trans_rollback(); 
            return false; 
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
        
    }
    
    function UpdateNoParent()
    {
        $this->db->trans_begin();
        
        $table = $this->_table_pages;
        
        // fetch titles for menu
        $this->db->select('Wiki_page_id, Parent_wiki_page_id');
        $query = $this->db->get($table);  
  
         // any result?
         if ($query && $query->num_rows() > 0)
         {
             // ok, continue..
            $result = $query->result();            
            
            // loop thru result
            $no_parents = array();
            foreach($result as $row)
            {
                if ( empty($row->Parent_wiki_page_id)==false)
                {
                    // check if parent exists
                    $parent_found = false;
                    foreach($result as $row2)
                    {
                        if ( (int)$row->Parent_wiki_page_id  == (int)$row2->Wiki_page_id )
                        {
                            // parent found!
                            $parent_found = true;
                            break;
                        }
                    }
                    
                    // no parent?
                    if ($parent_found==false) 
                    {
                        array_push($no_parents, $row->Wiki_page_id);    
                    }
                }    
            }
            
            // update children to be a mainpage instead
            foreach($no_parents as $row)
            {
                $sql = "UPDATE $table SET Parent_wiki_page_id = NULL WHERE Wiki_page_id = $row;";
                $this->db->query($sql);
            }
            
            // all ok
            $this->db->trans_commit(); 
            return true;
         }
         else
         {
             // nothing to do
             $this->db->trans_rollback();
            return false;
         } 
    }
    
    function SearchByWord($word)
    {
        $word = $this->db->escape("%$word%");
        
        $table = $this->_table_pages;
        $sql = "SELECT Title, Wiki_page_id FROM $table WHERE `Text` LIKE $word OR `Title` LIKE $word;";
        $query = $this->db->query($sql);
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            $result = $query->result();
            return $result;
         }
         else
         {
             // else return empty array
            return array();
         } 
    }
    
    function SearchByTag($tag)
    {       
        $tag = $this->db->escape("%$tag%");
            
        $table1 = $this->_table_tags;
        $table2 = $this->_table_pages;
        $sql = "SELECT $table2.Title, $table2.Wiki_page_id FROM $table1 JOIN $table2 ON $table1.Wiki_page_id = $table2.Wiki_page_id WHERE $table1.`Tag` LIKE $tag;";
        $query = $this->db->query($sql);
        
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            $result = $query->result();
            return $result;
         }
         else
         {
             // else return empty array
            return array();
         }  
    }
    
    
    function UpdatePageAndTags($Wiki_page_id, $instance_id, $title, $text, $tags, $parent, $order, $author, $updated)
    {
        $this->db->trans_begin();     
        
        //---------------------------
        // get current version
        $old_page = $this->FetchPage($Wiki_page_id, $instance_id);
        $old_tags = $this->FetchPageTags($Wiki_page_id);
        
        //---------------------------
        // copy page to history
        $data = array(
            'Wiki_page_id' => $old_page->Wiki_page_id,
            'Instance_id' => $old_page->Instance_id,
            'Project_id' => $old_page->Project_id,
            'User_id' => $old_page->User_id,
            'Created' => $old_page->Created,
            'Title' => $old_page->Title,
            'Text' => $old_page->Text,
            'Order' => $old_page->Order,
            'Version' => $old_page->Version
        );
        
        // additional data that can be null?
        if (empty($old_page->Parent_wiki_page_id)==false)
        {
            $data['Parent_wiki_page_id'] = $old_page->Parent_wiki_page_id;    
        }
        if (empty($old_page->Updated)==false)
        {
            $data['Updated'] = $old_page->Updated;    
        }
        
        $res = $this->db->insert($this->_table_history, $data);
        
        // check result
        if ($res == false)
        {
            // something went wrong
            // rollback transaction and return false
            $this->db->trans_rollback();
            return false;  
        }
        
        // fetch new id
        $new_wiki_page_history_id = $this->db->insert_id();
        
        //---------------------------
        // copy tags to history
        foreach($old_tags as $otag)
        {
            $data = array(
                'Wiki_page_history_id' => $new_wiki_page_history_id,
                'Tag' => $otag->Tag
            ); 
         
            $res = $this->db->insert($this->_table_tags_history, $data);
            
            // check result
            if ($res == false)
            {
                // something went wrong
                // rollback transaction and return false
                $this->db->trans_rollback();
                return false;  
            }
        }        
        
        //---------------------------
        // update current version

        // package
        $data = array(
            'User_id' => $author,
            'Updated' => $updated,
            'Title' => $title,
            'Text' => $text,
            'Order' => $order,
            'Version' => (int)$old_page->Version+1
        );
        
        // additional data that can be null?
        if (empty($parent)==false)
        {
            $data['Parent_wiki_page_id'] = $parent;    
        }
        
        // update db
        $this->db->where( array('Wiki_page_id' => $Wiki_page_id, 'Instance_id' => $instance_id) );
        $res = $this->db->update($this->_table_pages, $data);
        
        // check result
        if ( $this->db->affected_rows() == false )
        {
            // rollback transaction and return false
            $this->db->trans_rollback();
            return false;  
        }
        
        //-----------------------------
        // update tags for new version?
        if (empty($tags)==false)
        {
           // delete all old tags for current
           $this->db->delete($this->_table_tags, array('Wiki_page_id' => $Wiki_page_id));

            // save new tags
            foreach($tags as $tag)
            {
                // package data
                $data = array(
                    'Wiki_page_id' => $Wiki_page_id,
                    'Tag' => $tag
                ); 
             
                // save current tag
                $res = $this->db->insert($this->_table_tags, $data);
                
                // check result
                if ($res == false)
                {
                    // something went wrong
                    // rollback transaction and return false
                    $this->db->trans_rollback();
                    return false;  
                }
            }  
        }
        
        //-------------------------------------
        // delete old version(s) if more than 10
        
        $this->db->select('Wiki_page_history_id');
        $this->db->where( array('Wiki_page_id' => $Wiki_page_id, 'Instance_id' => $instance_id) );
        $this->db->order_by('Version DESC');
        $query = $this->db->get($this->_table_history);
        
        // any result?
        if ($query && $query->num_rows() > 0)
        {
            $result = $query->result();
            
            // check count
            $cnt = count($result);
            if ( $cnt > 10 )
            {
                // more than 10; delete
             
                $delete_ids = array();
             
                // more than one?
                if ( $cnt > 11 )
                {    
                    // start loop at 10 (array starts at 0 )
                    for($n=10; $n<$cnt; $n++)    
                    {
                        $row = $result[$n];
                        array_push($delete_ids, $row->Wiki_page_history_id);  
                    }
                }
                else if ( $cnt == 11 )
                {
                    // only one
                    $row = $result[10]; // array starts at 0
                    array_push($delete_ids, $row->Wiki_page_history_id);
                }
              
                // delete from page_history ( tags_history  will also be deleted to due foreign key cascade rule)
                $this->db->where('Wiki_page_history_id', $delete_ids[0]);
                if (count($delete_ids)>1)
                {
                    $len = count($delete_ids);
                    for($n=1; $n<$len; $n++)
                    {
                        $row = $delete_ids[$n];
                        $this->db->or_where('Wiki_page_history_id', $delete_ids[$n]);  
                    }
                }
                $res = $this->db->delete($this->_table_history);
                if ( $res == false )
                {
                    $this->db->trans_rollback(); 
                    return false; 
                }

            }
        }
        
        //----------------------------- 
        // all ok! commit!
        $this->db->trans_commit(); 
        return true;
    }
}  
  
?>

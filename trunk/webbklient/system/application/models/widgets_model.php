<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the class Widgets
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Widgets_model extends Model  {
    
     private $_table = "Project_Widgets";
     private $_table2 = "Widgets";
     private $_table3 = "Widget_Positions";
     
     // will be set to true in delete-query if widget
     // is in development
     private $_deleteQuery_InDevMode = false;
     
			/**
			* Returns the widget_id. Inputs can be the id of the windowinstance (Project_Widget_id)
			*	or the name of the widget (Widget_name).
			* 
			* @param mixed $inp
			* @return mixed
			*/
     function GetWidgetId($inp)
     {
				if(is_int($inp)) {
					$t1 = $this->_table;
					$this->db->select("$t1.Widget_id");
					$this->db->from($t1);
					$this->db->where(array("$t1.Project_widgets_id" => $inp));
				} else {
					$table2 = $this->_table2;
					$this->db->select("$table2.Widget_id");
					$this->db->from($table2);
					$this->db->where(array("$table2.Widget_name" => $inp));
				}
				
				$query = $this->db->get();
				$row = $query->row();
				
				if(empty($row)) {
					return false;
				} else {
					return $row->Widget_id;
				}

     }
     
			/**
			* Returns the name of an specific widget. 
			* 
			* @param mixed $inp
			* @return mixed
			*/
     function GetProjectWidgetName($project_widget_id)
     {
		$table2 = $this->_table2;
		$this->db->select("$table2.Widget_name");
        $this->db->from($table2);
        $this->db->where(array("$table2.Project_widgets_id" => $project_widget_id));
        
        $query = $this->db->get();
        $row = $query->row();
        
        if(empty($row)) {
        	return false;
        } else {
        	return $row->Widget_name;
        }

     }
     
     function getDefaultWidgets()
     {
        $defaultWidgets = $this->db->select("Default_Widgets.Widgets_id");
        $this->db->from("Default_Widgets");
        $query = $this->db->get();
        
        // any result?
         if ($query && $query->num_rows() > 0)
         {
            $result = $query->result();
            return $result;
         }
         else
         {
            return false;
         }
     }
     
     function addDefaultWidgets($project_id)
     {
        $default_widgets = $this->getDefaultWidgets();
        
        foreach($default_widgets as $widget) {
            $id = $widget->Widgets_id;
            $this->AddProjectWidget($project_id, $id);
        }
     }
     
    /**
    * Get all widgets for a specific project
    * 
    * @param string $projectID
    * @param bool active (optional, default true)
    * @return mixed
    */
     function GetProjectWidgets($projectID, $active=true)
     {
         // prepare
         $active = ($active === true ? 1 : 0);
				
         // run query
         $table1 = $this->_table;
         $table2 = $this->_table2;
         $this->db->select("$table1.*, $table2.*");
         $this->db->from($this->_table);
         $this->db->join($table2, "$table1.Widget_id = $table2.Widget_id");
         $this->db->where(array("$table1.Is_active" => $active, "$table1.Project_id" => $projectID));
         $this->db->order_by("$table1.Order ASC");
         $query = $this->db->get();
         
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            $result = $query->result();
            return $result;
         }
         else
         {
            return false;
         }
     }
    
    /**
    * This will get all widgets in the database
    * @return mixed
    */
    function GetStoredWidgets()
    {
         // run query
         $query = $this->db->get_where($this->_table2);
         
         // any result?
         if ($query && $query->num_rows() > 0)
         {
            $result = $query->result();
            return $result;
         }
         else
         {
            return false;
         }
    }
    
    /**
    * This will add a list of names to the database (table Widgets)
    * 
    * @param array $names
    * @return bool
    */
    function AddStoredWidgets($names)
    {
        // start a transaction; all or nothing
        $this->db->trans_begin();
        
        foreach($names as $row)
        {
            $this->db->insert($this->_table2, array('Widget_name' => $row) );
        
            // nothing changed?
            if ( $this->db->affected_rows() == 0 )
            {
                // roll back transaction and return false
                $this->db->trans_rollback();
                return false;
            }
        } 
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit();
        return true;
    }
    
    /**
    * This will delete a list of names to the database (table Widgets)
    * 
    * @param array $names
    * @return bool
    */
    function DeleteStoredWidgets($names)
    {
        
        // start a transaction; all or nothing
        $this->db->trans_begin();
        
        foreach($names as $row)
        {
            // is widget in devmode?
            $query = $this->db->get_where($this->_table2, array('Widget_name' => $row));
            $result = $query->result();
            if ($result[0]->In_development == '1')
            {
                // yes, do a override
                $this->_deleteQuery_InDevMode = true;
				$this->db->trans_rollback();
                return false;
            }
            
            
            $res = $this->db->delete($this->_table2, array('Widget_name' => $row) );
        
            // nothing changed?
            if ( $res == false )
            {
                // roll back transaction and return false
                $this->db->trans_rollback();
                return false;
            }
        } 
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit();
        return true;
         
    }
    
    function AddProjectWidget($projectid, $widgetid)
    {
        // start transaction (function will FAIL if transaction is not used)  
        $this->db->trans_begin();  
        
        // insert row
        $this->db->insert($this->_table, array('Project_id' => $projectid, 'Widget_id' => $widgetid, 'Is_active' => 1));
     
        // nothing changed?
        if ( $this->db->affected_rows() == 0 )
        {   
            // rollbak db and return false 
            $this->db->trans_rollback();
            return false;
        }
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit(); 
        return true;
    }
    
    function isDefault($widgetid)
    {
        // run query
         $query = $this->db->get_where('Default_Widgets', array('Widgets_id' => $widgetid));
         
         // any result?
         if ($query && $query->num_rows() > 0) {
            return true;
         } else {
            return false;
         }
    }
    
    function DeleteProjectWidget($project_widget_id)
    {
        // start transaction (function will FAIL if transaction is not used)
        $this->db->trans_begin();  
        
        $widgetid = $this->GetWidgetId((int) $project_widget_id);
        $isDefault = $this->isDefault($widgetid);
        

        $res = $this->db->delete($this->_table, array('Project_widgets_id' => $project_widget_id) );

        
        // nothing changed?
        if ($res == false )
        {   
            // rollbak db and return false
            $this->db->trans_rollback();
            return false;
        }
        
        if($isDefault) {
            // rollbak db and return false
            $this->db->trans_rollback();
            return false;
        }
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit(); 
        return true;

    }
    
    
    /**
    * Checks if last delete-query returned
    * that the widget is in development. Will
    * also reset the status upon exit.
    * 
    * @return bool
    */
    function CheckDeleteQuery()
    {
        $return_value = $this->_deleteQuery_InDevMode;
        $this->_deleteQuery_InDevMode = false;   
        return $return_value;
    }
    
    /**
    * Get all widget positions for a specific user and
    * project.
    * 
    * @param int $User_id
    * @param int $Project_id
    * @return mixed
    */
    function GetWidgetPositions($User_id, $Project_id) {
        
          $query = $this->db->get_where( $this->_table3, array( 'User_id' => $User_id, 'Project_id' => $Project_id ) ); 
          
          // any result?
          if ($query && $query->num_rows() > 0)
          {
             $result = $query->result();
             return $result;
          }
          else
          {
             return false;
          }
    }
    
    /**
    * Save last position of window for a specific
    * user and project.
    * 
    * @param int $current_project_id
    * @param int $uid
    * @param int $project_widget_id
    * @param int $is_maximized
    * @param int $last_x
    * @param int $last_y
    * @return bool
    */
    function SaveWidgetPosition($current_project_id, $uid, $project_widget_id, $is_maximized, $last_x, $last_y) {
        
        // query if we should insert or update
        $query = $this->db->get_where( $this->_table3, array( 'Project_widgets_id' => $project_widget_id, 'User_id' => $uid, 'Project_id' => $current_project_id ) );
        
        // handle result
        if ($query && $query->num_rows() > 0) {
            // update    
            
            $data = array(
                'Is_maximized' => $is_maximized,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y
            ); 
            
            $this->db->where( array( 'Project_widgets_id' => $project_widget_id, 'User_id' => $uid, 'Project_id' => $current_project_id ) );
            return $this->db->update($this->_table3, $data);
            
        } else {
            // save new
            
            $data = array(
                'Project_id' => $current_project_id,
                'Project_widgets_id' => $project_widget_id,
                'User_id' => $uid,
                'Is_maximized' => $is_maximized,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y
            );
            
            $this->db->insert($this->_table3, $data);
         
            // nothing changed?
            if ( $this->db->affected_rows() == 0 )
            {   
                // something went wrong
                return false;
                
            } else {
                
                // all ok
                return true;
                
            }
            
        }            
    }
}
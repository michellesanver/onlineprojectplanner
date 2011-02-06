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
     * @param int $widgetId
     * @return string
     */
	 function GetWidgetName($widgetId)
	 {
		$table2 = $this->_table2;
		$this->db->select("$table2.Widget_name");
		$this->db->from($table2);
		$this->db->where(array("$table2.Widget_id" => $widgetId));

		$query = $this->db->get();
		$row = $query->row();

		if(empty($row))
		{
			return false;
		}
		else
		{
			return $row->Widget_name;
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
     
	 /**
	 * Get all default widgets
	 *
	 * @param bool $allColumns (default false -> only returns Widgets_id)
	 * @return mixed
	 */
     function getDefaultWidgets($allColumns=false)
     {
		
		// all columns?
		if ($allColumns===false) {
			// only Widgets_id
			$this->db->select("Default_Widgets.Widgets_id");
		} else {
			// all columns
			$this->db->select("Default_Widgets.*");
		}
		
		// get from db
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
    * @param array $names (array of objects, one object has attribute name(string) and in_development(bool))
    * @return bool
    */
    function AddStoredWidgets($names)
    {
        // start a transaction; all or nothing
        $this->db->trans_begin();
        
        foreach($names as $row)
        {
	       // escape and process values for db
	       $widget_name = $this->db->escape($row->name); 
	       $in_development = ($row->in_development==true ? "'1'" : "'0'" ); // saved as tinyint in database
		   $is_core = ($row->is_core===true ? "'1'" : "'0'" ); // saved as tinyint in database
	       $minimum_role = $row->minimum_role;
	       
	       // is minimum_role NOT null?
	       if (strtolower($minimum_role) != 'null') {
		    $minimum_role = $this->db->escape( ucfirst(strtolower($minimum_role)) ); // add qoutes
	       }
	       
	       // create sql-query (active db will fail beause of 'Minimum_role' has a default value of null)
	       $sql = "INSERT INTO `".$this->_table2."` (`Widget_name`, `In_development`, `Minimum_role`, `Is_core`) VALUES ($widget_name, $in_development, $minimum_role, $is_core)";
	  
	       // run query
	       $this->db->query($sql);
        
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
    * @param array $names (array of strings)
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
            
	  // test if query returned one match (this may fail if multiple people are
	  // in the system at the same time and more than one has a delete in progress)
	  if ($query && $query->num_rows() == 1) {
	       
	       $result = $query->row(0);
	       if ($result->In_development == '1')
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
	    
        } 
        
        // else; all ok! commit transaction and return true
        $this->db->trans_commit();
        return true;
         
    }
    
    /**
     * Will update table 'Widgets' with new values (from syncronization)
     *
     * @param array $widgets (array of objects)
     * @return bool
     */
    function UpdateStoredWidgets($widgets)
    {
        // start transaction (function will FAIL if transaction is not used)  
        $this->db->trans_begin();
	
		// loop thru array
		foreach ($widgets as $row) {
		
			   // escape and process values for db
			   $widget_id = $this->db->escape($row->widget_id); 
			   $in_development = ($row->in_development==true ? "'1'" : "'0'" ); // saved as tinyint in database
			   $is_core = ($row->is_core=true ? "'1'" : "'0'" ); // saved as tinyint in database
			   $minimum_role = $row->minimum_role;
			   
			   // is minimum_role NOT null?
			   if (strtolower($minimum_role) != 'null') {
				$minimum_role = $this->db->escape( ucfirst(strtolower($minimum_role)) ); // add qoutes
			   }
		  
			   // create sql
			   $sql = "UPDATE `".$this->_table2."` SET  `Is_core` = $is_core, `In_development` = $in_development, `Minimum_role` = $minimum_role WHERE `Widget_id` = $widget_id";
		  
			   // run query
			   $this->db->query($sql);
			
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
	 * Add a widget to a project. If $instance_name is empty (default value)
	 * then the instance will have the same name as the widget.
	 *
	 * @param int $projectid
	 * @param int $widgetid
	 * @param string $instancename (default empty)
	 **/
    function AddProjectWidget($projectid, $widgetid, $instancename="")
    {
        // start transaction (function will FAIL if transaction is not used)  
        $this->db->trans_begin();  
        
		// empty name?
		if (empty($instancename)) {
		  
		  // fetch default name and handle result
		  $query = $this->db->get_where($this->_table2, array('Widget_id'=>$widgetid));
		  if ($query->num_rows()==1) {
			   
			   // save name
			   $result = $query->row(0);
			   $instancename = $result->Widget_name;
			   
		  } else {
			   
			   // failsafe; should not happen
			   $instancename = "Unkown (DB Err)";
			   
		  }
		  
		}
		
        // insert row
        $this->db->insert($this->_table, array('Project_id' => $projectid, 'Widget_id' => $widgetid, 'Is_active' => 1, 'Widget_instance_name' => $instancename));
     
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
    * @param int $is_open 
    * @param int $last_x
    * @param int $last_y
    * @param int $width
    * @param int $height
    * @return bool
    */
    function SaveWidgetPosition($current_project_id, $uid, $project_widget_id, $is_maximized, $last_x, $last_y, $is_open, $width, $height) {
        
        // query if we should insert or update
        $query = $this->db->get_where( $this->_table3, array( 'Project_widgets_id' => $project_widget_id, 'User_id' => $uid, 'Project_id' => $current_project_id ) );
        
        // handle result
        if ($query && $query->num_rows() > 0) {
            // update    
            
            $data = array(
                'Is_maximized' => $is_maximized,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y,
                'Is_open' => $is_open,
                'Width' => $width,
                'Height' => $height
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
                'Is_open' => $is_open,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y,
                'Width' => $width,
                'Height' => $height
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
    
    /**
    * Save status for a window (open or not)
    * @param int $project_id
    * @param int $uid
    * @param int $project_widget_id
    * @param int $is_open
    * @param int $last_x
    * @param int $last_y
    * @param int $width
    * @param int $height
    */
    function UpdateWidgetStatus($project_widget_id, $project_id, $uid, $is_open, $is_maximized, $last_x, $last_y, $width, $height) {
    
        // query if we should insert or update
        $query = $this->db->get_where( $this->_table3, array( 'Project_widgets_id' => $project_widget_id, 'User_id' => $uid, 'Project_id' => $project_id ) );
        
        // handle result
        if ($query && $query->num_rows() > 0) {
            // update 
            
            $data = array(
                'Is_maximized' => $is_maximized,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y,
                'Is_open' => $is_open,
                'Width' => $width,
                'Height' => $height
            ); 
            
            $this->db->where( array( 'Project_widgets_id' => $project_widget_id, 'User_id' => $uid, 'Project_id' => $project_id ) );
            return $this->db->update($this->_table3, $data);
        
        } else {
            // save new
            
            $data = array(
                'Project_id' => $project_id,
                'Project_widgets_id' => $project_widget_id,
                'User_id' => $uid,
                'Is_maximized' => $is_maximized,
                'Is_open' => $is_open,
                'Last_x_position' => $last_x,
                'Last_y_position' => $last_y,
                'Width' => $width,
                'Height' => $height
            );
            
            $this->db->insert($this->_table3, $data);
 
        }
        
    }
}
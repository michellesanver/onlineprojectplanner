<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about Widgets.. also a helper-class.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Widgets
{
    private $_CI = null; 
        
    private $_widgets = array();
	private $_folder_widget_names = array(); // used by _parseWidget

	private $_core_widget_dir = "application/widgets/_core";
    private $_widget_dir = "application/widgets";
    private $_generic_icon_image = "images/buttons/generic_plugin_icon.png";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
    private $_delay_widgets_sync = false; // default value
    private $_delay_widgets_sync_minutes = 15; // default value
    
    function __construct() {
        // get CI instance
        $this->_CI = & get_instance();
        
       // load database model
       $this->_CI->load->model('Widgets_model');
       $this->_CI->load->library('project_member');
       
       // any widget error?
       $widget_error = $this->_CI->session->userdata('widget_save_error');
       if ($widget_error == false) 
       {
	    // get settings from config about widgets sync
	    $this->_delay_widgets_sync = (bool)$this->_CI->config->item('delay_widgets_sync', 'webclient');
	    $this->_delay_widgets_sync_minutes = (int)$this->_CI->config->item('delay_widgets_sync_minutes', 'webclient');

	    // is it a ajax_call from the user?
	    if ( defined('IS_AJAX') && IS_AJAX == true ) {
		
		// log a message
	        log_message('debug', 'Scanning for widgets have been disabled due to AJAX call');
		
	    // does uri contain "common_variables" ?
	    } else if ( preg_match('/common_variables/', $_SERVER['REQUEST_URI']) ) {		
		
		// log a message
	        log_message('debug', 'Scanning for widgets have been disabled due to call from URI "common_variables"');
		
	    // read all folders with widgets at start
	    } else {
		
		$this->_readWidgets();
	    }
       }
       else
       {
           // clear error
           $this->_CI->session->unset_userdata('widget_save_error');
       }
    }


    /**
    * Read Widgets from folders
    * 
    * - used in widgethandler to bypass IS_AJAX override
    */
    function ManualReadWidgets() {
		// if empty; read widgets
		if (empty($this->_widgets)) {
			
			// log a message
			log_message('debug', 'Manually reading widgets folder');
			
			// read all folders with widget
			$this->_readWidgets();   
		}
    }

	/**
	* A helper function to _readWidgets which will parse a widget,
	* fetch settings.xml etc.. new data will be saved to $this->_widgets
	* and $this->_folder_widget_names.
	*/
	private function _parseWidget($dir, $file) {
	
		// create a datastructure to save data like widget config xml-file
		$w = new stdClass();
		
		$w->name = $file;
		
		$settings = simplexml_load_file("$dir/$file/settings.xml");
		
		$w->about = (string)$settings->about;
		$w->version = (string)$settings->version;
		$w->link = (string)$settings->link;
		$w->author = (string)$settings->author;
		
		$w->icon = (string)$settings->icon;
		$w->icon_title = (string)$settings->icon_title;
		$w->widget_startfunction = (string)$settings->widget_startfunction;
		$w->widget_object = (string)$settings->widget_object;
		$w->allow_multiple = (string)$settings->allow_multiple;
		
		$w->files = array();
		
		// save which files to load on appliation init
		foreach ($settings->load->file as $row)
		{
			$ws = new stdClass();
			
			$ws->type = (string)$row->attributes()->type;
			$ws->filename = (string)$row;
			
			array_push($w->files, $ws);
		}
		
		// does setting in_development exist?
		if ($settings->in_development) {
			
			// is it true or false?
			if ((string)$settings->in_development=='true') {
			$w->in_development = true;
			} else {
			$w->in_development = false;
			}
			
		} else {
			
			// default value; false
			$w->in_development = false;
			
		}

		// does setting minimum_role exist?
		if ($settings->minimum_role) {
			
			// copy value
			$w->minimum_role = (string)$settings->minimum_role;
			
		} else {
			
			// default value; NULL
			$w->minimum_role = 'NULL';
			
		}
   
		
		// core-widget?
		if ( preg_match('/_core/i', $dir) ) {
			$w->is_core = true;
		} else {
			$w->is_core = false;
		}

		// save to private array for the class
		array_push($this->_widgets, $w); 
		
		// save data for delete-search in db
		$obj = new stdClass();
		$obj->name = $w->name;
		$obj->is_core = $w->is_core;
		$obj->in_development = $w->in_development;
		$obj->minimum_role = $w->minimum_role;
		array_push($this->_folder_widget_names, $obj );
	
	}

    /**
    * Initializer; will read and store information
    * about all widgets currently in the folder widgets/*
    */
    function _readWidgets()
    {
		// log a message
		log_message('debug', 'Scanning widgets folder');
      
        // set folder
        $dir = BASEPATH . $this->_widget_dir;
      
        // open folder
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' &&  $file != ".." && $file != ".svn" && is_dir("$dir/$file"))    
                {
                    // only load if settings-file exist
                    if (file_exists("$dir/$file/settings.xml")) {
                    
						// parse widget and settings.xml
						$this->_parseWidget($dir, $file);
					
					// matched core-dir?	
                    } else if ( $file == "_core" ) {
					
						$dir2 = BASEPATH . $this->_core_widget_dir;
					
						// scan through sub-folder
						if ($dh2 = opendir($dir2)) {
						
							while (($file2 = readdir($dh2)) !== false) {
							
								if ($file2 != '.' &&  $file2 != ".." && $file2 != ".svn" && is_dir("$dir2/$file2"))   {
								
									// only load if settings-file exist
									if (file_exists("$dir2/$file2/settings.xml")) {
									
										// parse core-widget and settings.xml
										$this->_parseWidget($dir2, $file2);
										
									}
								
								}
							
							}
							
							closedir($dh2);
						}
					
					}
                }
            }
            closedir($dh);       
	
            // also fetch rows from database and update if needed
            $stored_widgets = $this->_CI->Widgets_model->GetStoredWidgets();
	    
			// log a message
			log_message('debug', 'Running widgets syncronization');
	    
			// check delay of sync
			if ( $this->_delay_widgets_sync == true ) {
	    
				$next_sync = $this->_CI->session->userdata('next_widgets_sync');
				if ( $next_sync != false ) { // compare time if not false (session->userdata returns false if not set)
				
					$current_time = time();
					if ( (int)$next_sync > $current_time ) {
					
					// ------------
					//
					// abort sync    
					//
					// ------------
					 
					log_message('debug', 'Widgets syncronization has been delayed');
					return;
					
					} else {
						// time to do a new sync; set new time
						$this->_CI->session->set_userdata('next_widgets_sync', strtotime('+'.$this->_delay_widgets_sync_minutes.'min'));
					}
					
				} else {
					// nothing set; save new time
					$this->_CI->session->set_userdata('next_widgets_sync', strtotime('+'.$this->_delay_widgets_sync_minutes.'min'));
				}
			}
	    
			// run sync or not (add and delete)?
            if (count($stored_widgets) != count($this->_widgets))
            {
		
                // -------------------------------------------
                // update database (widgets syncronization)
                // -------------------------------------------
                
                // storage for widgets to delete or add
                $widget_delete = array(); 
                $widget_add = array();
	        
                // any widgets in database?
                if ($stored_widgets!=false)
                {
                   
                    //-------------------------------------------------------
                    // loop thru db results (scan for widgets to delete in database)
                    foreach ($stored_widgets as $row)
                    {
						// search if name exist
						$current_row = null;
						foreach ($this->_folder_widget_names as $row2) {
							
							if ($row2->name == $row->Widget_name) {
								// set flag and exist
								$current_row = $row2;
								break;
							}
							
						}
	
                        // does the name from database exist in folders?
                        if ( is_null($current_row) ) { // is_null returns true or mixed
                            
							// push to array for delete from db
                            array_push($widget_delete, $row->Widget_name);
			    
                        }
			
                    }
                    
                    //--------------------------------------------------------
                    // loop thru folder results (scan for folders to add)
                    foreach ($this->_folder_widget_names as $row)
                    {
						$current_row = null;
						foreach($stored_widgets as $row2) {
							if ($row->name == $row2->Widget_name) {
								$current_row = $row2;
								break;
							}
						}
		    
                        // does the name from database exist in folders?
						if ( is_null($current_row) ) { // is_null returns true or mixed
                        
                            // push to array for add to db
                            array_push($widget_add, $row);
			    
                        }
                    }
		    
                }
                else
                {
                     //--------------------------------------- 
                     // add ALL folders     
                     
                     $widget_add = $this->_folder_widget_names;
                }
   
  
                //---------------------------------------
                // delete from db?    
                if ( empty($widget_delete) == false)
                {

                    if ($this->_CI->Widgets_model->DeleteStoredWidgets($widget_delete) == false )
                    {
                        // development mode?
                        if ( $this->_CI->Widgets_model->CheckDeleteQuery() == false)
                        {
                            // nope, continue to log error
                       
                            log_message('Error','#### => Panic! Failed to delete old widget-names from database.');
                            
                            // logout user if logged in
                            if ( $this->_CI->user->IsLoggedIn() )
                            {
                                $this->_CI->user->logout();
                                
                                // create a new session since it is destroyed in logout
                                @session_start();    
                            }
                            
                            // set error
                            $this->_CI->session->set_userdata('widget_save_error', true); // skip next call to readwidgets
                            $this->_CI->session->set_userdata('errormessage','Panic! Unable to update widgets in database.');   
                      
                            // redirect and exit
                            redirect('account/login');
                            return;
                        }
                    }
                    else
                    {
                        log_message('debug','Class Widgets deleted '.count($widget_delete).' widgets from database.');
                    } 
                }
 
                //---------------------------------------
                // add new widget-names to db?
                if ( empty($widget_add) == false )
                {
                    if ($this->_CI->Widgets_model->AddStoredWidgets($widget_add) == false)
                    {
                        // failed to add
                        log_message('Error','#### => Panic! Failed to add new widget-names to database.');
                        
                        // logout user if logged in
                        if ( $this->_CI->user->IsLoggedIn() )
                        {
                            $this->_CI->user->logout();
                            
                            // create a new session since it is destroyed in logout
                            @session_start();    
                        }
                        
                        // set error
                        $this->_CI->session->set_userdata('widget_save_error', true); // skip next call to readwidgets
                        $this->_CI->session->set_userdata('errormessage','Panic! Unable to update widgets in database.');   
                  
                        // redirect and exit
                        redirect('account/login');
                        return;
                    }
                    else
                    {
                        log_message('debug','Class Widgets added '.count($widget_add).' new widgets to database.');
                    } 
                }
  
			//--------------------------------------------------------------
			log_message('debug', 'Widgets syncronization completed');
	
	    // count matched, check if db is empty; then check for updates
	    } else if ($stored_widgets!=false) {
		
			// storage for widgets to update
			$widget_update = array();
			
			//---------------------------------------------------------------
			// loop thru db results (scan for widgets to update in database)
			foreach ($stored_widgets as $row)
			{
				// search if name exist
				$current_row = null;
				foreach ($this->_folder_widget_names as $row2) {
				
					if ($row2->name == $row->Widget_name) {
						// set flag and exist
						$current_row = $row2;
						break;
					}
				
				}
				
				// does the name from database exist in folders?
				if ( empty($current_row) == false ) { // is_null returns true or mixed
				
					$row_update = false;
					
					// does in_development match in database and settings.xml?
					if ($row->In_development == '1' && $current_row->in_development != true) {
					
						// set flag
						$row_update = true;
						
					}
					
					// minium_role; convert null to string 'null'
					$db_role = ( empty($row->Minimum_role) ? 'NULL' : $row->Minimum_role );
					
					// minium_role; check if rows does not match    
					if ( strtolower($db_role) != strtolower($current_row->minimum_role) ) {
					
						// set flag
						$row_update = true;
						
					}
					
					// update core-flag?
					if ($row->Is_core == '1' && $current_row->is_core != true) {
					
						// set flag
						$row_update = true;
						
					}
					
					// any update?
					if ($row_update === true) {
						
						// add id for easier update in
						$current_row->widget_id = $row->Widget_id; 
						
						// push for update
						array_push($widget_update, $current_row);
						
					}
				}
				
			}
		
			//log_message('debug', '##### => $widget_update has '.var_export($widget_update,true));
			
			//---------------------------------------
			// update widgets in db?    
			if ( empty($widget_update) == false)
			{
			   if ($this->_CI->Widgets_model->UpdateStoredWidgets($widget_update) == false) {
				
					// failed to update
					log_message('Error','#### => Panic! Failed to update widgets in database.');
				
			   } else {
				
					log_message('debug','Class Widgets updated '.count($widget_update).' widgets in database.');
			   }
			}
			
			//--------------------------------------------------------------
			log_message('debug', 'Widgets syncronization completed');
			
		} else { // end if (count($stored_widgets) != count($this->_widgets)) ..
			
			// log a message
			log_message('debug', 'No widgets to syncronize');
			
		}
            
        } // end if ($dh = opendir($dir)) ..      
    
	
		// clear array for scanned widgets
		$this->_folder_widget_names = array();
	
    }
    
    /**
    * This function will return html (script-tag) for all widgets
    * (when initializing application) for a specified project 
    * 
    * @param int $projectID 
    * @return string
    */
    function GetProjectJavascripts($projectID)
    {
        // add a tracemessage to log
        log_message('debug','#### => Library Widgets->GetProjectJavascripts');
        
        $type = "javascript";
        $jsSTR = '<script type="text/javascript" src="%s"></script>'."\n";
        
        return $this->_LoadFileType($projectID, $type, $jsSTR);
    }
    
    /**
    * This function will return html (link-tag) for all widgets
    * (when initializing application) for a specified project.
    * If $for_inline is set to true then the return-string can
    * be used as injected inline-css (@import)
    * 
    * @param int $projectID 
    * @param bool $for_inline (optional, default false)
    * @return string
    */
    function GetProjectStylesheets($projectID, $for_inline=false)
    {
        // add a tracemessage to log
        log_message('debug','#### => Library Widgets->GetProjectStylesheets');
        
        $type = "css";
        $cssSTR = "";
        if ($for_inline == false)
        {
            $cssSTR = '<link href="%s" rel="Stylesheet" type="text/css" />'."\n";
        }
        else
        {
            $cssSTR = '<style type="text/css">@import url(%s);</style>'."\n";    
        }
        
        return $this->_LoadFileType($projectID, $type, $cssSTR);
    }
    
    /**
    * Re-factored internal function for  GetProjectJavascripts, GetProjectStylesheets
    * 
    * @param int $projectID
    * @param string $type
    * @param string $loadSTR
    * @return mixed
    */
    private function _LoadFileType($projectID, $type, $loadSTR)
    {
        // fetch all for project 
        $project_widgets = $this->_GetProjectWidgets($projectID);
        
        // any widgets for current project?
        if ( empty($project_widgets) ) return ""; // return just empty then
        
				
		// Init the js that will handle the window object and everything common for all widget-windows
		$returnSTR = "";

		// prepare data
		$base_url = $this->_CI->config->item('base_url')."system/";

		// loop trough all widgets for the project
		$found_count = 0;
		foreach ($project_widgets as $row)
		{

			// match current widget for project with a row in all widgets 
			foreach ($this->_widgets as $row2)
			{
				if ( (string)$row2->name == (string)$row->Widget_name )    
				{
					//log_message('debug','Widgets->_LoadFileType() has '.count($row2->files).' files for widget '.$row->Widget_name);
					
					// scan through array of files
					foreach ($row2->files as $row3)
					{
						
						// is the type javascript?
						if (strtolower($row3->type) == $type)
						{
							$path = $base_url.$this->_widget_dir.'/';
						
							// is it a core-widget?
							if ( (int)$row->Is_core == 1 ) {
								// use another path
								$path = $base_url.$this->_core_widget_dir.'/';
							}
						
							// print and replace %s with the real value
							$returnSTR .= sprintf($loadSTR, $path.$row2->name.'/'.$row3->filename);       
							
						}
					}
					
					// ok, go to next widget in outer loop
					break;   
				}
			}
        }
    
        // return the result
        return $returnSTR; 
    }
    
    
    /**
    * This function will return html with icons for all
    * icons found in the widgets-folder.
    * 
    * @return string
    */
    function GetAllIcons()
    {
        // add a tracemessage to log
        log_message('debug','#### => Library Widgets->GetAllIcons');
        
        $returnSTR = "";
        
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s" title="%s"><img src="%s" alt="" /></a><p>%s</p></div>'."\n";
        $base_url = $this->_CI->config->item('base_url')."system/";
        
        // scan through all widgets that was found
        $pos = 0;
        foreach ($this->_widgets as $row)
        {
            $widget_object = $row2->widget_object;
            $about = $row2->about;
            $icon_div = "widget_icon".($pos+1);
            $function = "Desktop.open_widget('".$row2->widget_startfunction."', '$icon_div', '".$widget_object."')"; // open_widget is a global function in common.js
            $title = ($row2->icon_title != "" ? $row2->icon_title : "");
            $icon = ($row2->icon != "" ? $base_url.$this->_widget_dir.'/'.$row2->name.'/'.$row2->icon : $base_url."../".$this->_generic_icon_image);
            
            // replace %s with the real value
            $returnSTR .= sprintf($divSTR, ($pos+1), $function, $about, $icon, $title);
            
            // add one position
            $pos++;
            
            // prepare data
            /*$function = ($row->icon_startfunction != "" ? $row->icon_startfunction.'();' : "");
            $title = ($row->icon_title != "" ? $row->icon_title : "");
            $icon = ($row->icon != "" ? $base_url.$this->_widget_dir.'/'.$row->name.'/'.$row->icon : $base_url."../".$this->_generic_icon_image);
            
             // print and replace %s with the real value
            $returnSTR .= sprintf($divSTR, $function, $icon, $title);   */
            
        }

        // return the result
        return $returnSTR; 
    }
    
	function GetAllIconsAsArray()
	{
		
		$widget_array = array();
		$base_url = $this->_CI->config->item('base_url')."system/";
		
		foreach($this->_widgets as $widget)
		{
			$path =  $base_url.$this->_widget_dir.'/';
			// is it a core-widget?
			if ( $widget->is_core == true ) {
				// use another path
				$path = $base_url.$this->_core_widget_dir.'/';
			}
		
			// create full path to icon
			$icon = ($widget->icon != "" ? $path.$widget->name.'/'.$widget->icon : $base_url."../".$this->_generic_icon_image);
			
			// add data to array
			$widget_array[$widget->name]['icon'] = $icon;
			$widget_array[$widget->name]['icon_title'] = $widget->icon_title;	
			$widget_array[$widget->name]['id'] = $this->_CI->Widgets_model->GetWidgetId($widget->name);
		}
		
		return $widget_array;
	}
	
    function GetProjectIconsAsArray($projectID) 
    {
    	
   		$project_widgets = $this->_GetProjectWidgets($projectID, true); // get new data with forced parameter

   		$widget_array = array();
   		$base_url = $this->_CI->config->item('base_url')."system/";
   		$allicons = $this->GetAllIconsAsArray();

   		if(!empty($project_widgets)) 
   		{
   			foreach ($project_widgets as $widget)
	        {
	            $project_widgets_id = (int) $widget->Project_widgets_id;
	            $widgetid = $this->_CI->Widgets_model->GetWidgetId($project_widgets_id);
            	$icon = $allicons[$widget->Widget_name]['icon'];
            	$widget_array[$widget->Project_widgets_id]['name'] = $allicons[$widget->Widget_name]['icon_title'];
            	$widget_array[$widget->Project_widgets_id]['icon'] = $icon;
            	$widget_array[$widget->Project_widgets_id]['widgetid'] = $widgetid;
                $widget_array[$widget->Project_widgets_id]['default'] = $this->_CI->Widgets_model->isDefault($widgetid);
				$widget_array[$widget->Project_widgets_id]['instance_name'] = $widget->Widget_instance_name;

   		   }
        }
		
   		return $widget_array;
   }
   
    /**
    * This function will return html with icons for
    * a project. 
    * 
    * @param int $projectID
    * @return string
    */
    function GetProjectIcons($projectID)
    {
		// add a tracemessage to log
		log_message('debug','#### => Library Widgets->GetProjectIcons');
		
		// fetch all for project 
		$project_widgets = $this->_GetProjectWidgets($projectID);
		
		// any widgets for current project?
		if ( empty($project_widgets) ) return ""; // return just empty then
		
		// get all settings for last position
		// (all widgets for this project and the current user)
		$uid = $this->_CI->user->getUserID();
		$positions = $this->_CI->Widgets_model->GetWidgetPositions($uid, $projectID);
		
		// loop trough all widgets for the project
		$returnData = array();
        foreach ($project_widgets as $row)
        {
            // match current widget for project with a row in all widgets
            foreach ($this->_widgets as $row2)      
            {
				// correct name?
                if ( (string)$row2->name == (string)$row->Widget_name )    
                {
					$add_widget = false;
					
					// does widget have any specific role?
					if( is_null($row->Minimum_role) ) {
						
						// no role; just add widget
						$add_widget  = true;
						
					} else if($this->_CI->project_member->HaveRoleInCurrentProject($row->Minimum_role)) {
					
						// role is ok; add widget
						$add_widget  = true;
					}
					
					// add widget or not?
					if ( $add_widget === true ) {
						
						//
						// process widget data to be used in output
						//
						
						// create new object and add data for widget
						$wObj = new stdClass();
						$wObj->project_widgets_id = (int)$row->Project_widgets_id;
						$wObj->widget_startfunction = $row2->widget_startfunction;
						$wObj->widget_object_name = $row2->widget_object;
						$wObj->widget_instance_name = $row->Widget_instance_name;
						$wObj->widget_about = $row2->about;
						
						// get saved position
						$last_x = 30; //default value
						$last_y = 15; //default value
						$is_maximized = 'false'; //default value
						$width = 0; //default value 
						$height = 0; //default value 
						
						$is_open = false; //default value
						
						// search for last saved position?
						if (empty($positions)==false) {
							foreach($positions as $row3) {
								// any match?
								if ($row3->Project_widgets_id == $row->Project_widgets_id) {
									$last_x = (int)$row3->Last_x_position;    
									$last_y = (int)$row3->Last_y_position;
									$is_maximized = ((int)$row3->Is_maximized == 1 ? true : false);
									$is_open = ((int)$row3->Is_open == 1 ? true : false);
									$width = (int)$row3->Width;
									$height = (int)$row3->Height;
								}
							}
						}
                    
	                    // add data for last position (will be encoded to json-object later)
						$wObj->last_position = array(
														'last_x' => $last_x,
														'last_y' => $last_y,
														'is_maximized' => $is_maximized,
														'is_open' => $is_open,
														'width' => $width,
														'height' => $height
													);
						
						// get path for widget
						$base_url = $this->_CI->config->item('base_url')."system/";
					    $path = $base_url.$this->_widget_dir.'/';
						
						// is it a core-widget?
						if ( (int)$row->Is_core == 1 ) {
							// use another path
							$path = $base_url.$this->_core_widget_dir.'/';
						}
						
						// create and add icon path to object
						$wObj->icon = ($row2->icon != "" ? $path.$row2->name.'/'.$row2->icon : $base_url."../".$this->_generic_icon_image);
						
						// add widget to result array
						array_push($returnData, $wObj);
					}
					
				}
			}
		} // end foreach ($project_widgets...
	   
	    // return data to render with javascript
	    return json_encode($returnData);
    }
   
   private function _ClearWidgetCache()
   {
        $this->_CI->session->unset_userdata( array('cache_project_widgets','cache_project_widgets_timeout') );     
   }
   
   /**
   * load all widgets from database for a project
   * 
   * @param int $projectID
   * @param bool $forecedReload (optional, default false)
   * @return mixed
   */
   private function _GetProjectWidgets($projectID, $forecedReload=false)
   {
       // any cached data?
       /*$cached_project_widgets = ($forecedReload == true ? false : $this->_CI->session->userdata('cache_project_widgets') );
       $cache_project_widgets_timeout = ($forecedReload == true ? false : $this->_CI->session->userdata('cache_project_widgets_timeout') );
       
       // check timeout if found
       if ( $cached_project_widgets != false && $cache_project_widgets_timeout != false)
       {
            log_message('debug','Widgets->_GetProjectWidgets() has found cached data');
           
            $cached_project_widgets = unserialize($cached_project_widgets);
            $current_time = time();
            
            log_message('debug','Widgets->_GetProjectWidgets() has timeout values: $cache_project_widgets_timeout '.$cache_project_widgets_timeout.' ('.date('Y-m-d H:i:s',$cache_project_widgets_timeout).') > $current_time '.$current_time.' ('.date('Y-m-d H:i:s',$current_time).')');
            
            if ( (int)$cache_project_widgets_timeout > $current_time && empty($cached_project_widgets) == false )
            {
                log_message('debug','Widgets->_GetProjectWidgets() have found valid cache data');
                
                // all good! return cached data
                return $cached_project_widgets;
            }
            else
            {
                log_message('debug','Widgets->_GetProjectWidgets() will clear cashed data');
                
                // old data; clear! 
                $this->_ClearWidgetCache();
            }
       }*/
       
       // get from model
       $project_widgets = $this->_CI->Widgets_model->GetProjectWidgets($projectID); 
   
       //log_message('debug','Widgets->_GetProjectWidgets() has $project_widgets: '.var_export($project_widgets,true));
        
       // cache data (we will probably use it again very soon, loading icons and loading javascripts for example)
       $this->_CI->session->set_userdata('cache_project_widgets', serialize($project_widgets));
       $this->_CI->session->set_userdata('cache_project_widgets_timeout', strtotime('+2min'));
       
       // return
       return $project_widgets;
   }

   /**
    * This function will return false if Widget already exists in
    * Project and the Widgets settings.xml not allow multiple instances.
    *
    * @param int $projectId
    * @param int $widgetId
    * @return BOOL
    */

   function AllowedToInstanceProjectWidget($projectId, $widgetId)
   {
        // Get from model

        $projectWidgets = $this->_CI->Widgets_model->GetProjectWidgets($projectId);
        $widgetName = $this->_CI->Widgets_model->GetWidgetName($widgetId);
        $allowMultiple = true;

        if($widgetName != false)
        {
            // Get setting

            foreach($this->_widgets as $widget)
            {
                if($widget->name == $widgetName && $widget->allow_multiple == 'no')
                {
                    $allowMultiple = false;
                }
            }
        }

        // If not allowing multible instances, see if Project_widget exists

        if($allowMultiple != true && $projectWidgets != false)
        {
            foreach($projectWidgets as $projectWidget)
            {
                if($projectWidget->Widget_id == $widgetId)
                {
                    return false;
                }
            }
        }

        return true;
    }

    /**
    * This function will return all Widget icons
    * allowed to instance.
    *
    * @param int $projectId
    * @return array
    */

    function GetAllIconsAsArrayAllowedToInstance($projectId)
    {
        $widgetArray = array();
        $base_url = $this->_CI->config->item('base_url')."system/";

        // Get from model

        $projectWidgets = $this->_CI->Widgets_model->GetProjectWidgets($projectId);

        foreach($this->_widgets as $widget)
        {
            $allowed = true;

            // If  Widget exists in Project and not allowing multible instances, do not allow

            foreach($projectWidgets as $projectWidget)
            {
                if($projectWidget->Widget_name == $widget->name)
                {
                    if($widget->allow_multiple == 'no')
                    {
                        $allowed = false;
                    }
                }
                
            }

            if($allowed != false)
            {
                $icon = ($widget->icon != "" ? $base_url.$this->_widget_dir.'/'.$widget->name.'/'.$widget->icon : $base_url."../".$this->_generic_icon_image);
                $widgetArray[$widget->name]['icon'] = $icon;
                $widgetArray[$widget->name]['icon_title'] = $widget->icon_title;
                $widgetArray[$widget->name]['id'] = $this->_CI->Widgets_model->GetWidgetId($widget->name);
            }
        }

        return $widgetArray;
    }
   
}
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

    private $_widget_dir = "application/widgets";
    private $_generic_icon_image = "images/buttons/generic_plugin_icon.png";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
        // get CI instance
        $this->_CI = & get_instance();
        
       // load database model
       $this->_CI->load->model('Widgets_model');
       
       // any widget error?
       $widget_error = $this->_CI->session->userdata('widget_save_error');
       if ($widget_error == false) 
       {
            // read all folders with widgets at start
	        $this->_readWidgets();
       }
       else
       {
           // clear error
           $this->_CI->session->unset_userdata('widget_save_error');
       }
	}

    /**
    * Initializer; will read and store information
    * about all widgets currently in the folder widgets/*
    */
    function _readWidgets()
    {
        $folder_widget_names = array(); // save names here for delete-search in db
        
        // set folder
        $dir = BASEPATH . $this->_widget_dir;
      
        // open folder
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' &&  $file != ".." && $file != ".svn" && is_dir("$dir/$file"))    
                {
                    // only load if settings-file exist
                    if (file_exists("$dir/$file/settings.xml"))
                    {
                    
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
                        
                        $w->files = array();
                        
                        // save which files to load on appliation init
                        foreach ($settings->load->file as $row)
                        {
                            $ws = new stdClass();
                            
                            $ws->type = (string)$row->attributes()->type;
                            $ws->filename = (string)$row;
                            
                            array_push($w->files, $ws);
                        }
                        
                        // save to private array for the class
                        array_push($this->_widgets, $w);    
                        
                        // just save the name for delete-search in db
                        array_push($folder_widget_names, $w->name);
                    }
                }
            }
            closedir($dh);
            
            // also fetch rows from database and update if needed
            $stored_widgets = $this->_CI->Widgets_model->GetStoredWidgets();
            if (count($stored_widgets) != count($this->_widgets))
            {
                // -------------------------------
                // update database
                // -------------------------------
                
                
                // storage for widgets to delete or add
                $widget_delete = array(); 
                $widget_add = array();
                
                // add all folders?
                if ($stored_widgets!=false)
                {
                    // store just names of widgets from the db here
                    // in the first loop so we can compare for add
                    $db_widget_names = array();
                        
                    //--------------------------------------- 
                    // loop thru db results
                    foreach ($stored_widgets as $row)
                    {
                    
                        // does the name from database exist in folders?
                        if (in_array($row->Widget_name, $folder_widget_names)==false)
                        {
                            // push to array for delete from db
                            array_push($widget_delete, $row->Widget_name);    
                        }
                        else
                        {
                            // store name from db
                            array_push($db_widget_names, $row->Widget_name);
                        }
                    }
                    
                    //---------------------------------------
                    // loop thru folder results
                    foreach ($folder_widget_names as $row)
                    {
                    
                        // does the name from database exist in folders?
                        if (in_array($row, $db_widget_names)==false)
                        {
                            // push to array for add to db
                            array_push($widget_add, $row);    
                        }
                    }
                }
                else
                {
                     //--------------------------------------- 
                     // add ALL folders     
                     
                     $widget_add = $folder_widget_names;
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
                        log_message('debug','#### => Notice: Class Widgets deleted '.count($widget_delete).' widgets from database.');
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
                        log_message('debug','#### => Notice: Class Widgets added '.count($widget_add).' new widgets to database.');
                    }
                }
                
  
            }
            
        }       
    
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
        
        // prepare data
        $returnSTR = "";
        
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
                            // print and replace %s with the real value
                            $returnSTR .= sprintf($loadSTR, $base_url.$this->_widget_dir.'/'.$row2->name.'/'.$row3->filename);       
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
        
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s" title="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
        $base_url = $this->_CI->config->item('base_url')."system/";
        
        // scan through all widgets that was found
        $pos = 0;
        foreach ($this->_widgets as $row)
        {
            $widget_object = $row2->widget_object;
            $about = $row2->about;
            $icon_div = "widget_icon".($pos+1);
            $function = "open_widget('".$row2->widget_startfunction."', '$icon_div', '".$widget_object."')"; // open_widget is a global function in common.js
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
			$icon = ($widget->icon != "" ? $base_url.$this->_widget_dir.'/'.$widget->name.'/'.$widget->icon : $base_url."../".$this->_generic_icon_image);
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
            	$icon = $allicons[$widget->Widget_name]['icon'];
            	$widget_array[$widget->Project_widgets_id]['name'] = $allicons[$widget->Widget_name]['icon_title'];
            	$widget_array[$widget->Project_widgets_id]['icon'] = $icon;
         
            	
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
       
        // prepare data
        $returnSTR = "";
        $divSTR = '<div class="icon" id="widget_icon%s" state=""><a href="javascript:void(0);" onclick="%s" title="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
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
                    // prepare data
                    $widget_object = $row2->widget_object;
                    $about = $row2->about;
                    $icon_div = "widget_icon".($found_count+1);
                    $function = "open_widget('".$row2->widget_startfunction."', '$icon_div', '".$widget_object."')"; // open_widget is a global function in common.js
                    $title = ($row2->icon_title != "" ? $row2->icon_title : "");
                    $icon = ($row2->icon != "" ? $base_url.$this->_widget_dir.'/'.$row2->name.'/'.$row2->icon : $base_url."../".$this->_generic_icon_image);
                    
                    // replace %s with the real value
                    $returnSTR .= sprintf($divSTR, ($found_count+1), $function, $about, $icon, $title);

                    // add one widget found
                    $found_count++;
                    break;
                }
            }
        }

        // any mismatch with added widgets and widgets in project (=error in data, problably widget name)
        if ( count($project_widgets) != $found_count) return "ERROR IN WIDGET DATA; all was not added";
        
        // return the result
        return $returnSTR; 
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
       $cached_project_widgets = ($forecedReload == true ? false : $this->_CI->session->userdata('cache_project_widgets') );
       $cache_project_widgets_timeout = ($forecedReload == true ? false : $this->_CI->session->userdata('cache_project_widgets_timeout') );
       
       // check timeout if found
       if ( $cached_project_widgets != false && $cache_project_widgets_timeout != false)
       {
            //log_message('debug','Widgets->_GetProjectWidgets() has found cached data');
           
            $cached_project_widgets = unserialize($cached_project_widgets);
            $current_time = time();
            
            //log_message('debug','Widgets->_GetProjectWidgets() has timeout values: $cache_project_widgets_timeout '.$cache_project_widgets_timeout.' ('.date('Y-m-d H:i:s',$cache_project_widgets_timeout).') > $current_time '.$current_time.' ('.date('Y-m-d H:i:s',$current_time).')');
            
            if ( (int)$cache_project_widgets_timeout > $current_time && empty($cached_project_widgets) == false )
            {
                //log_message('debug','Widgets->_GetProjectWidgets() have found valid cache data');
                
                // all good! return cached data
                return $cached_project_widgets;
            }
            else
            {
                //log_message('debug','Widgets->_GetProjectWidgets() will clear cashed data');
                
                // old data; clear! 
                $this->_ClearWidgetCache();
            }
       }
       
       // get from model
       $project_widgets = $this->_CI->Widgets_model->GetProjectWidgets($projectID); 
       
       // cache data (we will probably use it again very soon, loading icons and loading javascripts for example)
       $this->_CI->session->set_userdata('cache_project_widgets', serialize($project_widgets));
       $this->_CI->session->set_userdata('cache_project_widgets_timeout', strtotime('+2min'));
       
       // return
       return $project_widgets;
   }
   
}
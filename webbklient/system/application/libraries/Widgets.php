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
   
    private $_debug = true; // log debug messages for this class
    
    private $_widgets = array();

    private $_widget_dir = "application/widgets";
    private $_generic_icon_image = "images/buttons/generic_plugin_icon.png";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
        // get CI instance
        $this->_CI = & get_instance();
        
        // read all folders with widgets at start
	    $this->_readWidgets();
	}

    /**
    * Initializer; will read and store information
    * about all widgets currently in the folder widgets/*
    */
    function _readWidgets()
    {
        // set folder
        $dir = BASEPATH . $this->_widget_dir;
        
        // open folder
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' &&  $file != ".." && $file != ".svn" && is_dir("$dir/$file"))    
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
                    $w->icon_startfunction = (string)$settings->icon_startfunction;
                    
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
                }
            }
            closedir($dh);
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
                    if ( $this->_debug) log_message('debug','Widgets->_LoadFileType() has '.count($row2->files).' files for widget '.$row->Widget_name);
                    
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
        $returnSTR = "";
        
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
        $base_url = $this->_CI->config->item('base_url')."system/";
        
        // scan through all widgets that was found
        foreach ($this->_widgets as $row)
        {
            // prepare data
            $function = ($row->icon_startfunction != "" ? $row->icon_startfunction.'();' : "");
            $title = ($row->icon_title != "" ? $row->icon_title : "");
            $icon = ($row->icon != "" ? $base_url.$this->_widget_dir.'/'.$row->name.'/'.$row->icon : $base_url."../".$this->_generic_icon_image);
            
             // print and replace %s with the real value
            $returnSTR .= sprintf($divSTR, $function, $icon, $title);   
            
        } 
        
        // return the result
        return $returnSTR; 
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
        // fetch all for project 
        $project_widgets = $this->_GetProjectWidgets($projectID);
        
        // any widgets for current project?
        if ( empty($project_widgets) ) return ""; // return just empty then
       
        // prepare data
        $returnSTR = "";
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
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
                    $function = ($row2->icon_startfunction != "" ? $row2->icon_startfunction.'();' : "");
                    $title = ($row2->icon_title != "" ? $row2->icon_title : "");
                    $icon = ($row2->icon != "" ? $base_url.$this->_widget_dir.'/'.$row2->name.'/'.$row2->icon : $base_url."../".$this->_generic_icon_image);
                    
                    // replace %s with the real value
                    $returnSTR .= sprintf($divSTR, $function, $icon, $title);

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
   
   /**
   * load all widgets from database for a project
   * 
   * @param int $projectID
   * @return mixed
   */
   private function _GetProjectWidgets($projectID)
   {
       // any cached data?
       $cached_project_widgets = $this->_CI->session->userdata('cache_project_widgets');
       $cache_project_widgets_timeout = $this->_CI->session->userdata('cache_project_widgets_timeout');
       
       // check timeout if found
       if ( $cached_project_widgets != false && $cache_project_widgets_timeout != false)
       {
            if ($this->_debug) log_message('debug','Widgets->_GetProjectWidgets() has found cached data');
           
            $cached_project_widgets = unserialize($cached_project_widgets);
            $current_time = time();
            
            if ($this->_debug) log_message('debug','Widgets->_GetProjectWidgets() has timeout values: $cache_project_widgets_timeout '.$cache_project_widgets_timeout.' ('.date('Y-m-d H:i:s',$cache_project_widgets_timeout).') > $current_time '.$current_time.' ('.date('Y-m-d H:i:s',$current_time).')');
            
            if ( (int)$cache_project_widgets_timeout > $current_time && empty($cached_project_widgets) == false )
            {
                if ($this->_debug) log_message('debug','Widgets->_GetProjectWidgets() have found valid cache data');
                
                // all good! return cached data
                return $cached_project_widgets;
            }
            else
            {
                if ($this->_debug) log_message('debug','Widgets->_GetProjectWidgets() will clear cashed data');
                
                // old data; clear! 
                $this->_CI->session->unset_userdata( array('cache_project_widgets','cache_project_widgets_timeout') );
            }
       }
       
       // load database model
       $this->_CI->load->model('Widgets_model');
       
       // get from model
       $project_widgets = $this->_CI->Widgets_model->GetProjectWidgets($projectID); 
       
       // cache data (we will probably use it again very soon, loading icons and loading javascripts for example)
       $this->_CI->session->set_userdata('cache_project_widgets', serialize($project_widgets));
       $this->_CI->session->set_userdata('cache_project_widgets_timeout', strtotime('+2min'));
       
       // return
       return $project_widgets;
   }
   
}
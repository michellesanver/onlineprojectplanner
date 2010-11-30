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
    private $_current_userID = null;
    
    private $_widgets = array();

    private $_widget_dir = "application/widgets";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
        // get CI instance
        $this->_CI = & get_instance();
        
        // get current userID for logged in user
        $this->_current_userID = $this->_CI->user->getUserID();
        
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
    * (when initializing application)
    * 
    * @return string
    */
    function GetWidgetJavascripts()
    {
        $returnSTR = "";
        $jsSTR = '<script type="text/javascript" src="%s"></script>'."\n";
        
        // scan through all widgets that was found
        foreach ($this->_widgets as $row)
        {
        
            // scan through array of files
            foreach ($row->files as $row2)
            {
                
                // is the type javascript?
                if (strtolower($row2->type) == "javascript")
                {
                    // print and replace %s with the real value
                    $returnSTR .= sprintf($jsSTR, site_url().$this->_widget_dir.'/'.$row->name.'/'.$row2->filename);       
                }

            }
            
        }
    
        // return the result
        return $returnSTR;
        
    }
    
    function GetWidgetStylesheets()
    {
        
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
        
        // scan through all widgets that was found
        foreach ($this->_widgets as $row)
        {
            // print and replace %s with the real value 
            $returnSTR .= sprintf($divSTR, $row->icon_startfunction.'();', site_url().$this->_widget_dir.'/'.$row->name.'/'.$row->icon, $row->icon_title);   
            
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
    function GetAllProjectIcons($projectID)
    {
        // fetch all for user 
        $user_widgets = $this->_GetProjectWidgets($projectID);
        
        // any widgets for current project?
        if ( empty($user_widgets) )
            return ""; // return just empty then
       
        // prepare data to be returned
        $returnSTR = "";
        
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
     
        // loop trough all widgets for the project
        $found_count = 0;
        foreach ($user_widgets as $row)
        {   
            // match current widget for project with all widgets
            foreach ($this->_widgets as $row2)      
            {   
                if ( (string)$row2->name == (string)$row->Widget_name )    
                {
                    // replace %s with the real value
                    $returnSTR .= sprintf($divSTR, $row2->icon_startfunction.'();', site_url().$this->_widget_dir.'/'.$row2->name.'/'.$row2->icon, $row2->icon_title);       
                    
                    // add one widget found
                    $found_count++;
                    break;
                }
            }
        }

        // any mismatch with added widgets and widgets in project (=error in data, problably widget name)
        if ( count($user_widgets) != $found_count)
            return "ERROR IN WIDGET DATA; all was not added";
        
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
       // load database model
       $this->_CI->load->model('Widgets_model');
       
       // get from model
       return $this->_CI->Widgets_model->GetProjectWidgets($projectID); 
   }
   
}
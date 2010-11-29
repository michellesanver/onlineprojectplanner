 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about Widgets.. also a helper-class.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Widgets
{

    private $_widgets = array();

    private $_widget_dir = "widgets";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
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
        $dir = BASEPATH."/".$this->_widget_dir;
        
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
    function printWidgetJavascripts()
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
    
    function printWidgetStylesheets()
    {
        
    }
    
    /**
    * This function will return html with icons for all
    * icons found in the widgets-folder.
    * 
    * @return string
    */
    function printIcons()
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
    
}
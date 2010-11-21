<?php


class Widgets
{

    private $_widgets = array();

    private $_widget_dir = "widgets";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
	    $this->_readWidgets();
	}


    function _readWidgets()
    {
        $dir = BASEPATH."/".$this->_widget_dir;
        
        
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' &&  $file != ".." && $file != ".svn" && is_dir("$dir/$file"))    
                {
                    
                    $w = new stdClass();
                    
                    $w->name = $file;
                    
                    $settings = simplexml_load_file("$dir/$file/settings.xml");
                    
                    $w->about = (string)$settings->about;
                    $w->version = (string)$settings->version;
                    $w->link = (string)$settings->link;
                    $w->author = (string)$settings->author;
                    
                    $w->icon = (string)$settings->icon;
                    $w->icon_startfunction = (string)$settings->icon_startfunction;
                    
                    $w->files = array();
                    
                    foreach ($settings->load->file as $row)
                    {
                        $ws = new stdClass();
                        
                        $ws->type = (string)$row->attributes()->type;
                        $ws->filename = (string)$row;
                        
                        array_push($w->files, $ws);
                    }
                    
                    
                    array_push($this->_widgets, $w);    
                }
            }
            closedir($dh);
        }       
    
    }

    
    function printWidgetJavascripts()
    {
        
        $jsSTR = '<script type="text/javascript" src="%s"></script>'."\n";
        
        
        foreach ($this->_widgets as $row)
        {
        
            foreach ($row->files as $row2)
            {
                
                if (strtolower($row2->type) == "javascript")
                {
                    printf($jsSTR, site_url().$this->_widget_dir.'/'.$row->name.'/'.$row2->filename);       
                }

            }
            
        }
    
        echo "\n";
        
    }
    
    function printWidgetStylesheets()
    {
        
    }
    
    function printIcons()
    {
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a></div>'."\n";
        
        
        foreach ($this->_widgets as $row)
        {
        
            printf($divSTR, $row->icon_startfunction.'();', site_url().$this->_widget_dir.'/'.$row->name.'/'.$row->icon);   
            
        } 
        

        echo "\n"; 
    }
    
}
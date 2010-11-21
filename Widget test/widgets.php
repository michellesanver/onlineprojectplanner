<?php


class Widgets
{

    private $_widgets = array();

    private $_widget_dir = "widgets";
    private $_icon_height = 48;
    private $_icon_width = 48;
    
	function __construct()
	{
        // läs in mapparna vid start
	    $this->_readWidgets();
	}


    function _readWidgets()
    {
        $dir = BASEPATH."/".$this->_widget_dir;
        
        // öppna mappen
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' &&  $file != ".." && $file != ".svn" && is_dir("$dir/$file"))    
                {
                    
                    // skapa datastruktur och spara ifrån xml-filen bl.a
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
                    
                    // spara filer som ska laddas
                    foreach ($settings->load->file as $row)
                    {
                        $ws = new stdClass();
                        
                        $ws->type = (string)$row->attributes()->type;
                        $ws->filename = (string)$row;
                        
                        array_push($w->files, $ws);
                    }
                    
                    // spara till intern array
                    array_push($this->_widgets, $w);    
                }
            }
            closedir($dh);
        }       
    
    }

    
    function printWidgetJavascripts()
    {
        
        $jsSTR = '<script type="text/javascript" src="%s"></script>'."\n";
        
        // loopa igenom alla widgets som hittades
        foreach ($this->_widgets as $row)
        {
        
            // loopa igenom arrayen med filer
            foreach ($row->files as $row2)
            {
                
                // är typen javascript?
                if (strtolower($row2->type) == "javascript")
                {
                    // skriv ut och ersätt %s med riktiga värden
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
        $divSTR = '<div class="icon"><a href="javascript:void(0);" onclick="%s"><img src="%s" width="'.$this->_icon_width.'" height="'.$this->_icon_height.'" /></a><br />%s</div>'."\n";
        
        // loopa igenom alla widgets som hittades
        foreach ($this->_widgets as $row)
        {
            // skriv ut och ersätt %s med riktiga värden 
            printf($divSTR, $row->icon_startfunction.'();', site_url().$this->_widget_dir.'/'.$row->name.'/'.$row->icon, $row->icon_title);   
            
        } 
        

        echo "\n"; 
    }
    
}
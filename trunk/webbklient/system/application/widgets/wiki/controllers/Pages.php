<?php


class Pages extends Controller
{
    
    
    private $_widget_name = "wiki";
    
    function __construct()
    {
        parent::Controller();    
        
        // load library
        $this->load->library_widget('Wiki_lib', null, 'Wiki');
    }
    
    
    function index()
    {
        
        // package some data for the view
        $widget_name = $this->_widget_name;
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            'wiki_menu' => $this->Wiki->GetMenuTitles(),
            'new_pages' => $this->Wiki->GetNewPages(),
            'last_updated_pages' => $this->Wiki->GetLastUpdatedPages()
        );
       
        // load a view for the widget
       $this->load->view_widget('start', $data);
        
    }   
    
    
    function get($Wiki_page_id)
    {
        
        echo "TEST: get/$Wiki_page_id";
        
    }
}
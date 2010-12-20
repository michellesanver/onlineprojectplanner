<?php


class Pages extends Controller
{
    
    
    private $_widget_name = "stopwatch";
    
    function __construct()
    {
        parent::Controller();    
    }
    
    
    function index()
    {
        
        // package some data for the view
        $widget_name = $this->_widget_name;
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/"
        );
       
        // load a view for the widget
       $this->load->view_widget('start', $data);
        
    }   
}
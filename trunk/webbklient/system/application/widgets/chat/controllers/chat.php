<?php

// class can be changed as needed

class Chat extends Controller {
    
    // the class is a regular Codeigniter controller
    // and inherits from CI

    function __construct()
    {
        parent::Controller();
    }
    
    // first function to be called if not specified in URL (Codeigniter)

    function index()
    {
        $widget_name = "chat";
  
        // package some data for the view

        $base_url = $this->config->item('base_url');

        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/"
        );
        
        // load a view for the widget
        // file is located in subfolder 'views'
        // for the widget

        $this->load->view_widget('start', $data); // view is loaded into an iframe (jquery plugin window)
        
    }
  
}

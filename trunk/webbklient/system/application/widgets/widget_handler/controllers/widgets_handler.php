<?php

// class can be changed as needed
class Widgets_handler extends Controller {
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();    
    }
    
    function _remap($method)
    {
        if (method_exists($this, $method))
        {
            $this->$method();
        } else {
            $this->index($method);
        }
    }
    
    // first function to be called if not specified in URL (Codeigniter)
    function index($addid = null)
    {
        
        $widget_name = "widget_handler";
        $this->load->library_widget('Widgetlib');
        
        if(isset($addid)) {
            $this->widgetlib->addWidgetToProject($addid);
        }
        
        if(isset($_POST["deleteid"])) {
            $this->widgetlib->removeWidgetFromProject($_POST["deleteid"]);
        }
        
        $widgets = $this->widgetlib->getAvailableWidgets();
        $project_widgets = $this->widgetlib->getProjectIcons();
        
        // package some data for the view
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'testing' => 'testing',
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            'allWidgets' => $widgets,
            'projectWidgets' => $project_widgets,
            'userID' => $this->user->getUserID() // used in a link
        );
        
        // load a view for the widget
        // file is located in subfolder 'views'
        // for the widget
       $this->load->view_widget('start', $data); // view is loaded into an iframe (jquery plugin window)
                
       
       // note; the function view_widget
       // is an extended function in Codeigniter
        
    }
    
    function sort()
    {
        $this->load->library_widget('Widgetlib');
        
        $positionarray = array();
        
        foreach($_POST['widgetslist'] as $position => $widget) {
           $this->widgetlib->setSort($widget, $position); 
        }
                
    }
    
  
  
}

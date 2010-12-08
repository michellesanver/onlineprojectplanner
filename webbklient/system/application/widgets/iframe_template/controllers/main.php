<?php

// class can be changed as needed
class Main extends Controller {
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();    
    }
    
    // first function to be called if not specified in URL (Codeigniter)
    function index()
    {
        $widget_name = "iframe_template";
  
        // package some data for the view
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            'userID' => $this->user->getUserID() // used in a link
        );
        
        // load a view for the widget
        // file is located in subfolder 'views'
        // for the widget
       $this->load->view_widget('start', $data); // view is loaded into an iframe (jquery plugin window)
       
       
       // note; the function view_widget
       // is an extended function in Codeigniter
        
    }
  
    function show_documentation()
    {
        $widget_name = "iframe_template";
  
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
       $this->load->view_widget('jquery_window_doc', $data); // view is loaded into an iframe (jquery plugin window)
        
    }
  
  
  function model_test()
  {
      // load a model inside subfolder 'mdel'
      $this->load->model_widget('testmodel');    
      
      
       // note; the function model_widget
       // is an extended function in Codeigniter
       
      
      // get data from model
      $members = $this->testmodel->getUsers();
      
      // output data from the model
      echo "<h1>Iframe template; Data from model</h1>";
      echo "<p><a href=\"javascript:window.back(-1);\"><< Back to previous page</a></p> ";
      echo "<table cellpadding=\"3\">";
      echo "<tr>";
      echo "<td><strong>Firstname</strong></td>";
      echo "<td><strong>Lastname</strong></td>";
      echo "<td><strong>Email</strong></td>";
      echo "</tr>";
      foreach ($members as $row)
      {
            echo "<tr>";
            echo "<td>$row->Firstname</td>";
            echo "<td>$row->Lastname</td>";
            echo "<td>$row->Email</td>";
            echo "</tr>";
      }
      echo "</table>";
  }
  
  
  function library_test()
  {
        // load a library
        $this->load->library_widget('Testlib'); 
      
       // note; the function library_widget
       // is an extended function in Codeigniter
      
      
       // output data from the library
       echo $this->testlib->randomFunctionName();
      
  }
  
  function edit_user($userID)
  {
      $widget_name = "iframe_template";  
      
      // load a library
      $this->load->library_widget('Testlib'); 
      
      // note; the function library_widget
      // is an extended function in Codeigniter
      
      
      // get user
      $user = $this->testlib->getUser($userID);
      
      // package some data for the view
      $base_url = $this->config->item('base_url');
      $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            // used in view
            'user' => $user,  
            'query' => $_SERVER['REQUEST_URI']
      );
      
      // load a view for the widget
      // file is located in subfolder 'views'
      // for the widget
      $this->load->view_widget('edit_user', $data); // view is loaded into an iframe (jquery plugin window)
  }
  
  
}

<?php

class Start extends Controller {

    function __construct()
    {
        parent::Controller();    
    }
    
    function index()
    {
        echo "inside test-widget controller Test->index";
    }
    
    function test_parameters($p1,$p2)
    {
        echo "inside test-widget controller Test->test_parameters";
        
        echo "<br/>";
        
        echo "parameters:: p1: $p1 p2: $p2";
    }
    
    function test_loader1()
    {
        echo 'loading library for widget..';
        $this->load->library_widget('testlib');
        
        echo "<br/>";
        
        // test library
        $this->testlib->some_function();
    }
    
    
    function test_loader2()
    {
        echo 'loading model for widget..';
        $this->load->model_widget('testuser');
        
        echo "<br/>";
        
        // test model
        $this->testuser->editMe();
    }  
    
    function test_view()
    {
        // test loading a view
        $this->load->view_widget('startpage');    
          
    }
 
    function test_view2()
    {
        // test loading a view from sub-folder
        $this->load->view_widget('admin/editview');    
          
    }
    
}

?>

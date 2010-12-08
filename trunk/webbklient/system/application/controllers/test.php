<?php

class Test extends Controller 
{
	function index()
	{
		$xhtml = "<H1>Test superwiki</H1>";
		
		$xhtml .= "<H3>Test av user</H3>";
		$this->load->model('user_model');
		$xhtml .= $this->user_model->Test();
		
		$data['xhtmlBody'] = $xhtml;
		$this->load->view('test_view', $data);
	}
	
    
    function widget_test1()
    {
        echo $this->widgets->GetProjectJavascripts(6);
        
        echo "Loading widgets for project id 6...";
        $widgets = $this->widgets->GetProjectIcons(6);
        if (empty($widgets))
        {
            echo " NONE found.";
            return;
        }
        
        echo "<br/><hr/><br/>";
        echo $widgets;
        
        echo "<br/><hr/><br/>";
        echo "Loading ALL widgets.."; 
        echo $this->widgets->GetAllIcons();
    }
		
		function log_error_test(){
			$id = $this->error->log('Detta är ett test, för att se så allt fungerar', $_SERVER['REMOTE_ADDR']);
			var_dump($this->error->GetFullLog());
			$this->error->RemoveLogEntity($id);
		}
    
}
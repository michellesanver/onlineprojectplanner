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
        $this->load->library('project_member');
        
        if($this->project_member->HaveRoleInCurrentProject('Admin')) {
            $widget_name = "widget_handler";
            $this->load->library_widget('Widgetlib');
            
            if(isset($addid)) {
                if($this->widgetlib->allowedToInstanceProjectWidget($addid) != false)
                {
                    $this->widgetlib->addWidgetToProject($addid);
                }
            }
            
            if(isset($_POST["widgetid"])) {
                $widget_id = $this->input->post('widgetid', true);
                $this->widgetlib->removeWidgetFromProject($widget_id);
            }
            
            $widgets = $this->widgetlib->getAvailableWidgets();
            $project_widgets = $this->widgetlib->getProjectIcons();
            
            // package some data for the view
            $base_url = $this->config->item('base_url');
            $data = array(
                'allWidgets' => $widgets,
                'projectWidgets' => $project_widgets
            );
            
            // any added widgets?
            if (isset($addid)) {
                // send to view that will trigger widgetbar to add new widget
                $data['addid'] = $addid;
               
                // get all project icons (returns a json-object)
                $project_widgets_json = json_decode( $this->widgetlib->getProjectIcons_JSON() );  
                
                // scan which widget that was added
                $new_project_id = 0;
                foreach ($project_widgets as $id => $widget) {
                    
                    if ( (int)$widget['widgetid'] == $addid) {
                        $new_project_id = $id; 
                        break;
                    }
                    
                }
                
                // get json-data
                $widget_name = "";
                $new_widget_json = "{}";
                foreach ($project_widgets_json as $row) {
                    
                    if ($row->project_widgets_id == $new_project_id) {
                        // save json data and name of widget
                        $new_widget_json = json_encode($row);
                        $widget_name = $row->widget_name;
                        break;    
                    }
                }
                
                // save to view
                $data['new_widget_json'] = $new_widget_json;
            }
            
            // load a view for the widget
            // file is located in subfolder 'views'
            // for the widget
           $this->load->view_widget('widget_handler', $data); // view is loaded into an iframe (jquery plugin window)
                    
           
           // note; the function view_widget
           // is an extended function in Codeigniter
        } else {
            die("You have no permission to view this widget.");
        }
        
        
    }
    
    function sort()
    {
        $this->load->library_widget('Widgetlib');
        
        foreach($_POST['widgetslist'] as $position => $widget) {
           $this->widgetlib->setSort($widget, $position); 
        }
        
        
        // create a json-object as response with the new positions to
        // update widgetbar
        
        echo json_encode($_POST['widgetslist']);
    }
    
  
    function rename() {
       
        // not an ajax request?
        if (!IS_AJAX) {
            die('Invalid request');
        }
       
        $this->load->library('project_member');
        
         // check authorization
        if ($this->project_member->HaveRoleInCurrentProject('Admin')) {
            
            // save new name
            
            // load librari
            $widget_name = "widget_handler";
            $this->load->library_widget('Widgetlib');

            // get values from post
            $widgetId = $this->input->post('widgetId', true);
            $instanceId = $this->input->post('instanceId', true);
            $widgetName = $this->input->post('widgetName', true);
            
            // prepare result json
            $jsonResult = array(
                            'dialogProcessingId' => $this->input->post('dialogProcessingId', true),
                            'dialogMessageId' => $this->input->post('dialogMessageId', true)
            );
            
            // check so they are not empty
            if ( empty($widgetName) ) {

                // set message to user
                $jsonResult['result'] = 'error';
                $jsonResult['message'] = 'Name ais required';
                
            // check if id is numeric and not empty
            } else if ( empty($widgetId) || is_numeric($widgetId) != true ) { // returns true or string

                // set message to user
                $jsonResult['result'] = 'error';
                $jsonResult['message'] = 'Error in values (Id)';
                
            // check length of name
            }  else if ( strlen($widgetName) > 30 ) {
           
                // set message to user
                $jsonResult['result'] = 'error';
                $jsonResult['message'] = 'Maximum length is 30 characters';
                
            // check character types
            }  else if ( preg_match('/[^a-z0-9()\sедц]/i', $widgetName) ) {
           
                // set message to user
                $jsonResult['result'] = 'error';
                $jsonResult['message'] = $widgetName;
            
            // else; SAVE!    
            } else {
                
               //
               // save to database
               //
               
               if ( $this->widgetlib->saveNewInstanceName($widgetId, $widgetName) == false )  {
                
                    // unable to save
                    $jsonResult['result'] = 'error';
                    $jsonResult['message'] = 'Unable to save to database';
                    
               } else {
                
                    // all ok
                    $jsonResult['result'] = 'ok';
                    $jsonResult['message'] = 'New name has been saved';
                    $jsonResult['instanceid'] = $instanceId;
                    $this->index();
					return;                    
               }
                
            }
            
            // output result as json
            header("Content-type: text/plain");
            echo json_encode($jsonResult);
            
            
        } else {
            //
            // no authorization
            //
            
            // output result as json
            $jsonResult = array(
                            'result' => 'error',
                            'message' => 'You have no permission to view this widget.',
                            
                            'dialogProcessingId' => $this->input->post('dialogProcessingId', true),
                            'dialogMessageId' => $this->input->post('dialogMessageId', true)
                        );
            
            header("Content-type: text/plain");
            echo json_encode($jsonResult);
        }
        
    }
}

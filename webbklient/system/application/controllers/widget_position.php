<?php

class Widget_position extends Controller {
    
    function Widget_settings()
    {
        parent::Controller();
        
       // load database model
       $this->load->model('Widgets_model');
    }
    
    /**
    * Save last position for a user and widget instance.
    */
    function save() {
        log_message('debug', '#### => Controller Widget_position->save');
		
		log_message('debug', '#### => $_POST: '.var_export($_POST,true));
		
        // fetch current project id
        $this->load->library('Project_lib', null, 'Project');
        $current_project_id = $this->Project->checkCurrentProject();
        
        // get current user
        $uid = $this->user->getUserID(); 
        
        // get project_widget_id aka instance id
        $project_widget_id = $this->input->post('project_widget_id', true);
        
        // get settings to save from post
        $is_maximized = strtolower( $this->input->post('is_maximized', true) );
        $is_maximized = ($is_maximized=='false' ? '0' : '1'); // saved as tinyint
        $last_x = $this->input->post('last_x', true);
        $last_y = $this->input->post('last_y', true);
      
        log_message('debug','$current_project_id: '.$current_project_id.' $project_widget_id: '.$project_widget_id.' $uid: '.$uid.' $is_maximized: '.$is_maximized.'  $last_x: '.$last_x.'  $last_y: '.$last_y);  
      
        // validate so required is not empty
        if ( $current_project_id == "" || $uid == "" || $is_maximized == "" || $last_x == "" || $last_y == "" || $project_widget_id == "") {
            echo 'Parameters is not correct.';
            return;
        }
        
        // save with model direct
        if ( $this->Widgets_model->SaveWidgetPosition($current_project_id, $uid, $project_widget_id, $is_maximized, $last_x, $last_y) == false ) {
            echo 'Failed to save to database.';
            return;  
        }
        
        // all went as expected
        echo 'Ok';
        
    }
    
}
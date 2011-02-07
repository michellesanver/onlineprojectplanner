<?php

class Widget_position extends Controller {
    
    function Widget_settings()
    {
        parent::Controller();
        
       // load database model
       $this->load->model('Widgets_model');
    }
    
    /**
    * Set status for widget
    */
    function update() {
        log_message('debug', '#### => Controller Widget_position->update');     
        
        // fetch current project id
        $this->load->library('Project_lib', null, 'Project');
        $current_project_id = $this->Project->checkCurrentProject();
        
        // get current user
        $uid = $this->user->getUserID();
        
        // get project_widget_id aka instance id
        $project_widget_id = $this->input->post('project_widget_id', true);
        
        // get settings to save from post 
		$is_maximized = '0'; // default value
		if (isset($_POST['is_maximized'])) {
			$is_maximized = strtolower( $this->input->post('is_maximized', true) );
			$is_maximized = ($is_maximized=='false' ? '0' : '1'); // saved as tinyint
		}
		
		$last_x = -1; // do not update in db-layer
		if (isset($_POST['last_x'])) {
			$last_x = $this->input->post('last_x', true);
        }
		
		$last_y = -1; // do not update in db-layer
		if (isset($_POST['last_y'])) {
			$last_y = $this->input->post('last_y', true);
        }
		
		$is_open = '0'; // default value
        if (isset($_POST['is_open'])) {
			$is_open = strtolower( $this->input->post('is_open', true) );
			$is_open = ($is_open=='false' ? '0' : '1'); // saved as tinyint
		}
		
		$width = -1; // do not update in db-layer		
		if (isset($_POST['width'])) {
			$width = $this->input->post('width', true);
		}
		
		$height = -1; // do not update in db-layer
		if (isset($_POST['height'])) {
			$height = $this->input->post('height', true);
		}
        
        // save with model
        $this->Widgets_model->UpdateWidgetStatus($project_widget_id, $current_project_id, $uid, $is_open, $is_maximized, $last_x, $last_y, $width, $height);
        
    }
    
    /**
    * Save last position for a user and widget instance.
    */
    function save() {
        log_message('debug', '#### => Controller Widget_position->save');
		
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
        $is_open = strtolower( $this->input->post('is_open', true) );
        $is_open = ($is_open=='false' ? '0' : '1'); // saved as tinyint
        $width = $this->input->post('width', true);
        $height = $this->input->post('height', true);
        
      
        // validate so required is not empty
        if ( $current_project_id == "" || $uid == "" || $is_maximized == "" || $last_x == "" || $last_y == "" || $project_widget_id == "") {
            echo 'Parameters is not correct.';
            return;
        }
        
        // save with model direct
        if ( $this->Widgets_model->SaveWidgetPosition($current_project_id, $uid, $project_widget_id, $is_maximized, $last_x, $last_y, $is_open, $width, $height) == false ) {
            echo 'Failed to save to database.';
            return;  
        }
        
        // all went as expected
        echo 'Ok';
        
    }
    
}
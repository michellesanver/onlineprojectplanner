<?php

// class can be changed as needed
class Notepad_contr extends Controller {
	
	private $_padList;
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();
        $this->_padList = null;
        
        $this->load->library(array('validation'));
        $this->load->model_widget("notepad_model");    
    }
    
    /**
     * returns the index view
     * 
     * @param number $projectId
     */
    function index($projectId)
    {
		// add a tracemessage to log
		log_message('debug','#### => Controller coop_note controller->index');
		
		//Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}

        if($this->user->IsLoggedIn()==false)
		{
			echo "You are not authenticated! <a href=\"".site_url('')."\">Login</a>";
			return;
		}
		
		// Starts the node connection
		$this->establishConnection();
		
		$data = array();
		$data['list'] = $this->load->view_widget('list', array( 'pads' => $this->getPadList($projectId) ), true);
		$data['pad'] = $this->load->view_widget('pad', null, true);
		$this->load->view_widget('start', $data);
    }
    
    /**
     * returns the list html
     * 
     * @param number $projectId
     */
    function reloadList($projectId){
    	$this->load->view_widget('list', array( 'pads' => $this->getPadList($projectId) ));
    }
    
    /**
     * returns the selected pad
     * 
     * @param number $projectId
     * @param mixed $padId
     */
    function select($projectId, $padId){
		// add a tracemessage to log
		log_message('debug','#### => Controller coop_note controller->select');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		if($padId != "new"){
	    	$pads = $this->getPadList($projectId);
	    	foreach($pads as $pad){
	    		if($pad['Pads_Id'] == $padId){
	    			$this->load->view_widget('pad', $pad);
	    			return;
	    		}
	    	}
		}
    	$this->load->view_widget('pad', null);
    }
    
    /**
     * Saves the new pad
     * 
     * @param number $projectId
     */
    function save($projectId){
		// add a tracemessage to log
		log_message('debug','#### => Controller coop_note controller->save');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		// Rules for the inputfields
		$this->validation->set_rules(array(
			"Name" => "required|max_length[45]|xss_clean",
			"Text" => "xss_clean|prep_for_form"
		));
		
		// Human names for the inputfields
		$this->validation->set_fields(array(
			"Name" => "Name",
			"Text" => "Text"
		));
		
		$data = array();
		
		if($this->validation->run()) {
			// Set updates
			$save = array(
				"Project_Id" => $projectId,
				"Name" => $this->validation->Name,
				"Text" => $this->validation->Text,
				"Created" => date('Y-m-d G:i:s'),
				"Last_Edit" => date('Y-m-d G:i:s')
			);
			
			// If validation is ok => send to library
			$newId = $this->notepad_model->Save($save);
			if($newId > 0) {
				$data["status"] = "ok";
				$data["status_message"] = "The notepad was saved!";
				$data["load"] = "reloadBoth";
				$data["loadparams"] = $newId;
			} else {
				$data["status"] = "error";
				$data["status_message"] = "The save failed!";
			}
		} else {
			$data["status"] = "error";
			$data["status_message"] = "Validation failed! <p>".$this->validation->error_string."</p>";
		}
		
		echo json_encode($data);
    	
		
    }
    
    /**
     * Removes a pad
     * 
     * @param number $padId
     */
    function delete($padId) {
    
		// add a tracemessage to log
		log_message('debug','#### => Controller coop_note controller->update');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
    
		if($this->notepad_model->Delete($padId)) {
			$data["status"] = "ok";
			$data["status_message"] = "The pad has been deleted!";
			$data["load"] = "reloadList";
		} else {
			$data["status"] = "error";
			$data["status_message"] = "The pad could not be deleted!";
		}
		
		echo json_encode($data);
    }
    
    /**
     * updates a pad
     * 
     * @param number $padId
     */
    function update($padId){
		// add a tracemessage to log
		log_message('debug','#### => Controller coop_note controller->update');
		
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		// Rules for the inputfields
		$this->validation->set_rules(array(
			"Text" => "xss_clean|prep_for_form"
		));
		
		// Human names for the inputfields
		$this->validation->set_fields(array(
			"Text" => "Text"
		));
		
		$data = array();
		
		if($this->validation->run()) {
			// Set updates
			$update = array(
				"Pads_Id" => $padId,
				"Text" => $this->validation->Text,
				"Last_Edit" => date('Y-m-d G:i:s')
			);
			
			// If validation is ok => send to library
			if($this->notepad_model->Update($update)) {
				$data["status"] = "ok";
				$data["status_message"] = "Update was successful!";
				$data["load"] = "reloadList";
			} else {
				$data["status"] = "error";
				$data["status_message"] = "Update failed!";
			}
		} else {
			$data["status"] = "error";
			$data["status_message"] = "Validation failed! <p>".$this->validation->error_string."</p>";
		}
		
		echo json_encode($data);
    }
    
    /**
     * Establishes a socket to the node server
     */
    function establishConnection(){
		// Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		
		$this->load->library_widget('curl', null, 'curl');
		$this->curl->create('http://www.pppp.nu:4001/');
		$this->curl->execute();
    }
    
    //===================
    // Helper functions
    //===================
    
    /**
     * returns the list
     * 
     * @param number $projectId
     */
    private function getPadList($projectId){
    	if($this->_padList == null){
    		$this->_padList = $this->notepad_model->getAllProjectPads($projectId);
    	}
    	return $this->_padList;
    }
}

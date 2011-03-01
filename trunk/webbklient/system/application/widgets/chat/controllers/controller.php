<?php

// class can be changed as needed
class Controller extends Controller {
    
    // the class is a regular Codeigniter controller
    // and inherits from CI
    function __construct()
    {
        parent::Controller();    
    }
    
    // first function to be called if not specified in URL (Codeigniter)
    function index($pwID)
    {
		// add a tracemessage to log
		//log_message('debug','#### => Controller Some_controller_name->index');
		
		/* Test if it's not an ajax-request
		if(IS_AJAX == false) {
			echo "You cant do this, that way!";
			return;
		}
		*/
    }
}

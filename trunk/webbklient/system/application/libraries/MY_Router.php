<?php

/**
* This class overrides functions in Codeigniter so paths
* for widgets is supported.
*
* @author Fredrik Johansson <tazzie76@gmail.com>
* @link https://code.google.com/p/onlineprojectplanner/
*/
class MY_Router extends CI_Router {

    
    function __construct()
    {
        parent::CI_Router();
    }
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * NOTE; this function is taken from Codeigniter 1.7.2 (Router Core Library)
	 * except the code for widget
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */	
	function _validate_request($segments)
	{        
		// is controller a widget?
		if ( strtolower($segments[0]) == 'widget' )
		{   
            // name of widget and does directory exist?     
            if ( isset($segments[1]) == false || is_dir(APPPATH."widgets/".$segments[1]) == false )
            {
                show_error("Widget trigger found in URL but invalid name and/or controller of widget.");
                return;    
            }              
           
            // remove trigger-word "widget"
			$segments = array_slice($segments, 1); 
		
			// core-widget?
			$is_core = false;
			if ( $segments[0] == '_core' ) {
			
				// yes, set flag and remove from array
				$is_core = true;
				$segments = array_slice($segments, 1); 
				
				// define a constant that is used in MY_Loader
				define('WIDGET_IS_CORE', true);
				
			} else {
			
				// define a constant (TO FALSE) that is used in MY_Loader
				define('WIDGET_IS_CORE', false);
				
			}
		
            // get name of widget and remove from array
            $name = $segments[0];
            $segments = array_slice($segments, 1);
        
		    // set path to widget-folder
			$widget_path = "";
			if ( $is_core === true ) {
			
				// core-widget
				$widget_path = "widgets/_core/$name/";
            
				// set directory to widget (traverse up one level so this library is compatible with CI base files)
				$this->set_directory("../widgets/_core/$name/controllers");
				
			} else {
			
				// no core-widget
				$widget_path = "widgets/$name/";
            
				// set directory to widget (traverse up one level so this library is compatible with CI base files)
				$this->set_directory("../widgets/$name/controllers");
				
            }
			
            // any controller?
            if ( isset($segments[0]) == false )
            {     
                // try controller with same name as widget name
                if ( file_exists(APPPATH.$widget_path.'controllers/'.ucfirst(strtolower($name)).EXT) )
                {
                    // set controller name
                    $segments[0] = $name;   
                }
                else
                {
                    // unkown controller
                    show_error("Widget trigger found in URL but controller was not found.");
                    return;  
                }
            }
               
           // set name of widget as a constant
           define('WIDGET_NAME', $name);
           
           // return controller name and parameters
           return $segments;
		}
        
	  
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{		
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);
			
			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');
			
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.$widget_path.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}
			
			}
          
			return $segments;
		}
		
		// Can't find the requested controller...
		show_404($segments[0]);
	}
	
    
}
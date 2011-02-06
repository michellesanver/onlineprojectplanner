<?php

/**
* This class extends the core to support folder-structure for
* widgets with loading libraries, models and views.
* 
* @author Fredrik Johansson <tazzie76@gmail.com>
* @link https://code.google.com/p/onlineprojectplanner/
*/
class MY_Loader extends CI_Loader {

    private $_widget_base_path = "";
    
    function __construct()
    {
        parent::CI_Loader();
        
		// core widget or not?
		if ( defined('WIDGET_IS_CORE') && WIDGET_IS_CORE === true ) {
		
			// create CORE widget basepath
			// (constant WIDGET_NAME is set in extended CI core library Router)
			if ( defined('WIDGET_NAME') ) $this->_widget_base_path = "../widgets/_core/".WIDGET_NAME."/";
		
		} else {
		
			// create widget basepath
			// (constant WIDGET_NAME is set in extended CI core library Router)
			if ( defined('WIDGET_NAME') ) $this->_widget_base_path = "../widgets/".WIDGET_NAME."/";
			
		}
    }
    
    /**
     * Library Loader (Widget) 
	 *
	 * NOTE; this function is taken from Codeigniter 1.7.2 (Loader Core Library)
	 *
     * This function lets users load and instantiate classes.
     * It is designed to be called from a user's app controllers.
     *
     * @access    public
     * @param    string    the name of the class
     * @param    mixed    the optional parameters
     * @param    string    an optional object name
     * @return    void
     */
    function library_widget($library = '', $params = NULL, $object_name = NULL) 
    {
		if ( defined('WIDGET_NAME') == false ) return false;
	
        if ($library == '')
        {
            return FALSE;
        }

        if ( ! is_null($params) AND ! is_array($params))
        {
            $params = NULL;
        }

        if (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->_ci_load_class($this->_widget_base_path."/libraries/$class", $params, $object_name);
            }
        }
        else
        {
            $this->_ci_load_class($this->_widget_base_path."/libraries/$library", $params, $object_name);
        }
        
        $this->_ci_assign_to_models();  
    }   
    
    /**
     * Model Loader (Widget)
     *
     * This function lets users load and instantiate models.
     *
     * @access    public
     * @param    string    the name of the class
     * @param    string    name for the model
     * @param    bool    database connection
     * @return    void
     */    
    function model_widget($model, $name = '', $db_conn = FALSE)
    {
		if ( defined('WIDGET_NAME') == false ) return false;
	
        // set new path
        $model = $this->_widget_base_path."models/$model";    
              
        // use function in core library then
        return $this->model($model, $name, $db_conn);
    }
    
    
    /**
     * Load View (Widget)
     *
     * This function is used to load a "view" file.  It has three parameters:
     *
     * 1. The name of the "view" file to be included.
     * 2. An associative array of data to be extracted for use in the view.
     * 3. TRUE/FALSE - whether to return the data or load it.  In
     * some cases it's advantageous to be able to return data so that
     * a developer can process it in some way.
     *
     * @param    string $view
     * @param    array $vars (optional)
     * @param    bool  $return (optional, default false)
     */
    function view_widget($view, $vars = array(), $return = FALSE)
    {
		if ( defined('WIDGET_NAME') == false ) return false;
		
        // set new path
        $view = $this->_widget_base_path."views/$view";    
      
        // use function in core library then
        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }
    
}
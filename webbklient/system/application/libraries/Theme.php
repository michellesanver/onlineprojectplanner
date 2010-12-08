<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all common init etc with the view..
* Also has a wrapper function for load->view (CI)
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Theme
{ 
    private $_CI = null;
    
    private $_site_title = "";
    private $_theme = "";
    private $_base_url = "";
    
    function __construct()
    {
        // get CI instance
        $this->_CI = & get_instance();
        
        // fetch from config
       $this->_site_title = $this->_CI->config->item('site_title', 'webclient');  
       $this->_theme = $this->_CI->config->item('theme', 'webclient');  
       $this->_base_url = $this->_CI->config->item('base_url');
       
       // load library widgets
       $this->_CI->load->library('widgets', 'user');
    }
    
    /**
    * Will return the name (and folder) of the current theme.
    * 
    * @return string
    */
    function GetThemeFolder()
    {
        return $this->_theme;
    }
    
    /**
    * Wrapper-function for CI's load->view. Also
    * initialies pre and post content parts of the view.
    * Theme-folder name is applied to $view
    * 
     * @param string $view
     * @param array  $vars (optional)
     * @param string $page_title (optional, added to head->title)
    */
    function view($view, $vars = array(), $page_title = "")
    {
        // add a tracemessage to log
        log_message('debug','#### => Library Theme->view');
        
        // any project set as current?
        $current_project_id = $this->_CI->session->userdata('current_project_id');
        
        // pre content
        $preContentData = array(
            'site_title' => $this->_site_title,
            'theme_folder' => $this->_theme,
            'base_url' => $this->_base_url,
            'is_logged_in' => $this->_CI->user->isLoggedIn(),
            'current_project_id' => $current_project_id
        );
        
        // add page title?
        if (empty($page_title)==false) $preContentData['page_title'] = $page_title;
        
				if($this->_CI->user->IsLoggedIn()) {
					$user = $this->_CI->user->getLoggedInUser();
					$preContentData['username'] = $user['Username'];
				}
        // which pre_content to load?
        if ( $current_project_id != false )
        {
           // load widgets javascript and css
           $preContentData['widget_javascript']  = $this->_CI->widgets->GetProjectJavascripts($current_project_id);
           $preContentData['widget_css']  = $this->_CI->widgets->GetProjectStylesheets($current_project_id);
           $preContentData['widget_bar'] = $this->_CI->widgets->GetProjectIcons($current_project_id);  
         
           // use project pre_content
           $this->_CI->load->view($this->_theme.'/common/project_pre_content', $preContentData); 
        }
        else
        {
            // no project set
            $this->_CI->load->view($this->_theme.'/common/pre_content', $preContentData);
        }
        
        // check if theme-folder is applied (shouldn't be)
        if ( preg_match('/'.$this->_theme.'/', $view) == false) $view = $this->_theme.'/'.$view;
        
        // add any data for project to main content?
        if ( $current_project_id != false )
        {

            
        }
        
        // content
        $this->_CI->load->view($view, $vars);
				
        // which post_content to load?
        if ($current_project_id != false )
        {
            // use project post_content
           $this->_CI->load->view($this->_theme.'/common/project_post_content', $preContentData);  
        }
        else
        {
            // no project set
            $this->_CI->load->view($this->_theme.'/common/post_content');
        }
    }
    
    
    
    
    
}
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
        // pre content
        $preContentData = array(
            'site_title' => $this->_site_title,
            'theme_folder' => $this->_theme,
            'base_url' => $this->_base_url
        );
        
        if (empty($page_title)==false) $preContentData['page_title'] = $page_title;
        
        $this->_CI->load->view($this->_theme.'/common/pre_content', $preContentData);

        
        // check if theme-folder is applied (shouldn't be)
        if ( preg_match('/'.$this->_theme.'/', $view) == false) $view = $this->_theme.'/'.$view;
        
        // content
        $this->_CI->load->view($view, $vars);
        

        // post content
       $this->_CI->load->view($this->_theme.'/common/post_content');
    }
    
    
    
    
    
}
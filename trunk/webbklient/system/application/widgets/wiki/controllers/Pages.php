<?php


class Pages extends Controller
{
    
    
    private $_widget_name = "wiki";
    
    function __construct()
    {
        parent::Controller();    
        
        // load library
        $this->load->library_widget('Wiki_lib', null, 'Wiki');
    }
    
    
    function index()
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }
        
        // package some data for the view
        $widget_name = $this->_widget_name;
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            'wiki_menu' => $this->Wiki->GetMenuTitles(),
            'new_pages' => $this->Wiki->GetNewPages(),
            'last_updated_pages' => $this->Wiki->GetLastUpdatedPages()
        );
       
        // load a view for the widget
       $this->load->view_widget('start', $data);
        
    }   
    
    //
    // This function is called by ajax
    //
    function get($Wiki_page_id)
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }
             
        // package and fetch data for view
        $data = array(
            'page' => $this->Wiki->GetPage($Wiki_page_id)
        );
            
        // no page found?
        if ( $data['page'] === false )
        {
            // string will be matched in javascript
            echo "PAGE NOT FOUND";
            return;
        }
        
        // add current version in history
        $currentVersion = new stdClass();
        $currentVersion->Wiki_page_history_id = null; // do NOT view this in history
        $currentVersion->Title = $data['page']->Title;
        $currentVersion->Version = $data['page']->Version;
        $currentVersion->Created = $data['page']->Created;
        $currentVersion->Updated = $data['page']->Updated;
        $currentVersion->Firstname = $data['page']->Firstname;
        $currentVersion->Lastname = $data['page']->Lastname;
        
        // get more data
        $data['history'] = $this->Wiki->GetHistory($Wiki_page_id);
        array_push($data['history'], $currentVersion);
        
        // show view
        $this->load->view_widget('page', $data); 
    }
    
    //
    // get a page from history - called by ajax
    //
    function get_history($Wiki_page_history_id)
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }  
       
        // fetch page from history
        $page = $this->Wiki->GetHistoryPage($Wiki_page_history_id);
       
        // no page found?
        if ( $page === false )
        {
            // string will be matched in javascript
            echo "PAGE NOT FOUND";
            return;
        }
        
        // show view
        $this->load->view_widget('page_history', array('page'=>$page));
    }
    
    
}
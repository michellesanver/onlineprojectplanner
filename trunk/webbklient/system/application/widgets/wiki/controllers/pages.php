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
    
    //
    // this will load the startpage
    // (if used internally then $ok_message or $error_message can be set)
    //
    function index($instance_id, $ok_message='', $error_message='')
    {            
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, display error just as with javascript and die
            echo $this->_GetDisplayError('Error 401','NOT AUTHORIZED');
            die();
        }
        
        // package some data for the view
        $widget_name = $this->_widget_name;
        $base_url = $this->config->item('base_url');
        $data = array(
			'instance_id' => $instance_id,
			
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            'wiki_menu' => $this->Wiki->GetMenuTitles($instance_id), // current project id is fetched in library
            
            'new_pages' => $this->Wiki->GetNewPages($instance_id),  // current project id is fetched in library  
            'last_updated_pages' => $this->Wiki->GetLastUpdatedPages($instance_id), // current project id is fetched in library  
            
            'changelog' => $this->Wiki->GetChangelog()
        );
       
        // any message set?
        if (empty($ok_message)==false)
        {
            $data['status'] = 'ok';
            $data['status_message'] = $ok_message;
        }
        else if (empty($error_message)==false)
        {
            $data['status'] = 'error';
            $data['status_message'] = $error_message;
        }
       
       // load content
       $data['content'] = $this->load->view_widget('start', $data, true);
       
        // load complete view for the widget
       $this->load->view_widget('common_layout', $data);
        
    }   
   
   //
   // internal function that gets called after a
   // new page is saved. will reload the view (just
   // as in index but load the specified page)
   //
    private function _index_new_page($wiki_page_id, $instance_id, $ok_message='', $error_message='')
    {   
         // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, display error just as with javascript and die
            echo $this->_GetDisplayError('Error 401','NOT AUTHORIZED');
            die();
        }
        
        // package some data for the view
        $widget_name = $this->_widget_name;
        $base_url = $this->config->item('base_url');
        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            
            'wiki_menu' => $this->Wiki->GetMenuTitles($instance_id),   // current project id is fetched in library 
            
            'new_pages' => $this->Wiki->GetNewPages($instance_id), // current project id is fetched in library 
            'last_updated_pages' => $this->Wiki->GetLastUpdatedPages($instance_id), // current project id is fetched in library 
            
            'changelog' => $this->Wiki->GetChangelog()
        );
      
        // any message set?
        if (empty($ok_message)==false)
        {
            $data['status'] = 'ok';
            $data['status_message'] = $ok_message;
        }
        else if (empty($error_message)==false)
        {
            $data['status'] = 'error';
            $data['status_message'] = $error_message;
        }
        
       // get page
       $data['page'] = $this->Wiki->GetPage($wiki_page_id, $instance_id );
    
  
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
        $data['history'] = $this->Wiki->GetHistory($wiki_page_id, $instance_id );
        array_push($data['history'], $currentVersion);
        
        $data['select_parents'] = $this->Wiki->GetTitlesWithoutChildren($instance_id );
        $data['delete_token'] = $this->_GenerateDeleteCode($wiki_page_id); 
      
        // add instance id for delete-link
        $data['instance_id'] = $instance_id;
    
        // get images for wysiwyg
        $data['wysiwyg_images'] = $this->Wiki->getUploadedImages($instance_id);
   
        // create path for uploaded images
        $data['wysiwyg_upload_path'] = $this->Wiki->getUploadedPath($instance_id);
      
        // populate variables for editform (so the view will work with edit)
        $data['form_title'] = $data['page']->Title;
        $data['form_text'] = $data['page']->Text;
        $data['form_order'] = $data['page']->Order; 
        $data['form_tags'] = $data['page']->Tags_string; 
        $data['form_parent'] = $data['page']->Parent_wiki_page_id;
      
        // load content
        $data['content'] = $this->load->view_widget('page', $data, true);
        
        
       // load complete view for the widget
       $this->load->view_widget('common_layout', $data);
    }
    
    //
    // get a new page (also edit and history)
    //
    function get($Wiki_page_id, $instance_id)
    {      
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }
          
        // use CI validation
        $this->load->library(array('validation'));
    
        // set validation rules
        $rules = array(
            "wiki_edit_title" => "max_length[100]|required|strip_tags|xss_clean",
            "wiki_edit_text" => "required|xss_clean"
        );   
        $this->validation->set_rules($rules);  
    
        // set names for validation errors
        $fields = array(
            "wiki_edit_title" => "Title",
            "wiki_edit_text" => "Text" 
        );
        $this->validation->set_fields($fields); 
        
        // prepare array for view
        $data = array();  
        
        // run validation
       if ($this->validation->run())
       {
            // validation ok! update page!    

            $form_title = $this->input->post('wiki_edit_title');
            $form_text = $this->input->post('wiki_edit_text');
            $form_parent = strip_tags( $this->input->post('wiki_edit_parent', true) ); // also do xss_clean
            $form_order = strip_tags( $this->input->post('wiki_edit_order', true) ); // also do xss_clean
         
            // update tags or not?
            $form_tags = "";
            $tags_update = $this->input->post('wiki_edit_tags_update');
            if ($tags_update == 'true' || $tags_update == true)
            {
                $form_tags = strip_tags( $this->input->post('wiki_edit_tags', true) ); // also do xss_clean    
            }
            
            // save with library
            if ( $this->Wiki->UpdatePage($Wiki_page_id, $instance_id, $form_title, $form_text, $form_tags, $form_parent, $form_order) != false )
            {
                // all ok!
                $data['status'] = "ok";
                $data['status_message'] = "Page has been saved";   
            }
            else
            {
                // no error was thrown
                $data['status'] = "error";
                $data['status_message'] = $this->Wiki->GetLastError();    
                
                // refill form data
                $data['form_title'] = $this->input->post('wiki_create_title');
                $data['form_text'] = $this->input->post('wiki_create_text');
                $data['form_tags'] = $this->input->post('wiki_create_tags');
                $data['form_parent'] = $this->input->post('wiki_page_parent');
                $data['form_order'] = $this->input->post('wiki_create_order');
            }
       }
         
        // add instance id for delete-link
        $data['instance_id'] = $instance_id;
        
        // get images for wysiwyg
        $data['wysiwyg_images'] = $this->Wiki->getUploadedImages($instance_id);
       
        // create path for uploaded images
        $data['wysiwyg_upload_path'] = $this->Wiki->getUploadedPath($instance_id);
                
        // package and fetch data for view
        $data['page'] = $this->Wiki->GetPage($Wiki_page_id, $instance_id);
            
        // no page found?
        if ( $data['page'] === false )
        {
            // string will be matched in javascript
            die("PAGE NOT FOUND");
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
        $data['history'] = array( $currentVersion );
        $data['history'] = array_merge($data['history'], $this->Wiki->GetHistory($Wiki_page_id, $instance_id));
        
        $data['select_parents'] = $this->Wiki->GetTitlesWithoutChildren($instance_id);
        $data['delete_token'] = $this->_GenerateDeleteCode($Wiki_page_id);
        
       // any errors set? 
       $errors = validation_errors();
       if ( empty($errors) == false || empty($this->validation->error_string) == false )
       {
            $data['status'] = "error";
            $data['status_message'] = 'Error(s): '.$errors.$this->validation->error_string;
            
            // refill form data
            $data['form_title'] = $this->input->post('wiki_edit_title');
            $data['form_text'] = $this->input->post('wiki_edit_text');
            $data['form_tags'] = $this->input->post('wiki_edit_tags');
            $data['form_parent'] = $this->input->post('wiki_edit_parent');
            $data['form_order'] = $this->input->post('wiki_edit_order');
            
            // set flag så the edit is displayed directly
            $data['show_edit'] = true;
       }
       else
       {  
            // populate variables for editform (so the view will work with edit)
            $data['form_title'] = $data['page']->Title;
            $data['form_text'] = $data['page']->Text;
            $data['form_order'] = $data['page']->Order; 
            $data['form_tags'] = $data['page']->Tags_string; 
            $data['form_parent'] = $data['page']->Parent_wiki_page_id;
       }
       
       
        // show view
        $this->load->view_widget('page', $data); 
    }
    
    //
    // alias of get to get a cleaner url for edit page
    //
    function update($Wiki_page_id, $instance_id)
    {
        return $this->get($Wiki_page_id, $instance_id);
    }
    
    //
    // get a page from history
    //
    function get_history($Wiki_page_history_id, $instance_id)
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }  
       
        // fetch page from history
        $page = $this->Wiki->GetHistoryPage($Wiki_page_history_id, $instance_id);
       
        // no page found?
        if ( $page === false )
        {
            // string will be matched in javascript
            echo "PAGE NOT FOUND";
            return;
        }
        
        // show view
        $this->load->view_widget('page_history', array('page'=>$page, 'instance_id'=>$instance_id));
    }
    
    //
    // create a new page
    //
    function create($instance_id)
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }   
    
        // use CI validation
        $this->load->library(array('validation'));
    
        // set validation rules
        $rules = array(
            "wiki_create_title" => "max_length[100]|required|strip_tags|xss_clean",
            "wiki_create_text" => "required|xss_clean"
        );   
        $this->validation->set_rules($rules);  
    
        // set names for validation errors
        $fields = array(
            "wiki_create_title" => "Title",
            "wiki_create_text" => "Text" 
        );
        $this->validation->set_fields($fields);
        
        // prepare array for view
        $data = array();
        
        // run validation
       if ($this->validation->run())
       { 
            // save data

            $form_title = $this->input->post('wiki_create_title');
            $form_text = $this->input->post('wiki_create_text');
            $form_tags = strip_tags( $this->input->post('wiki_create_tags', true) ); // also do xss_clean
            $form_parent = strip_tags( $this->input->post('wiki_page_parent', true) ); // also do xss_clean
            $form_order = strip_tags( $this->input->post('wiki_create_order', true) ); // also do xss_clean
            
            // send to library
            $new_wiki_page_id = $this->Wiki->SaveNewPage($instance_id, $form_title, $form_text, $form_tags, $form_parent, $form_order);
            
            // what was the result?
            if ( $new_wiki_page_id != false )
            {
                // all ok! show page then (also reload index and menu)
                $this->_index_new_page($new_wiki_page_id, $instance_id, 'New page has been saved'); // since function create is called with ajax we can use this and it won't affect the url
                return;
            }
            else
            {
                // no error was thrown
                $data['status'] = "error";
                $data['status_message'] = $this->Wiki->GetLastError();    
                
                // refill form data
                $data['form_title'] = $this->input->post('wiki_create_title');
                $data['form_text'] = $this->input->post('wiki_create_text');
                $data['form_tags'] = $this->input->post('wiki_create_tags');
                $data['form_parent'] = $this->input->post('wiki_page_parent');
                $data['form_order'] = $this->input->post('wiki_create_order');
            }
            
       }

       
       // show form and/or display errors

       // package some data for the view
       $widget_name = $this->_widget_name;
       $base_url = $this->config->item('base_url');
       $data['base_url'] = $base_url;
       $data['widget_url'] = site_url("/widget/$widget_name").'/';
       $data['widget_base_url'] = $base_url."system/application/widgets/$widget_name/";
       $data['wiki_menu'] = $this->Wiki->GetMenuTitles($instance_id);
       
       // any errors set?
       $errors = validation_errors();
       if ( empty($errors) == false || empty($this->validation->error_string) == false )
       {
            $data['status'] = "error";
            $data['status_message'] = 'Error(s): '.strip_tags($errors.$this->validation->error_string);
            
            // refill form data
            $data['form_title'] = $this->input->post('wiki_create_title');
            $data['form_text'] = $this->input->post('wiki_create_text');
            $data['form_tags'] = $this->input->post('wiki_create_tags');
            $data['form_parent'] = $this->input->post('wiki_page_parent');
            $data['form_order'] = $this->input->post('wiki_create_order');
       }
       
        // add instance id for delete-link
        $data['instance_id'] = $instance_id;
        
        // get images for wysiwyg
        $data['wysiwyg_images'] = $this->Wiki->getUploadedImages($instance_id);
       
        // create path for uploaded images
        $data['wysiwyg_upload_path'] = $this->Wiki->getUploadedPath($instance_id);
       
       // fetch all pages with no children for select
       $data['select_parents'] = $this->Wiki->GetTitlesWithoutChildren($instance_id);
    
       // show view
       $data['content'] = $this->load->view_widget('create', $data, true);
       
       // load complete view for the widget
       $this->load->view_widget('common_layout', $data);
    }
    
    function delete($Wiki_page_id, $token, $instance_id)
    {
        // is user logged in?
        if ($this->user->IsLoggedIn() == false)
        {
            // nope, then die
            die('NOT AUTHORIZED');
        }  
        
        // get code and clear in session
        $saved_token = $this->session->userdata('delete_token');
        $saved_id = $this->session->userdata('delete_id');
        $this->session->unset_userdata(array('delete_id','delete_token'));
        
        // verify code and id
        if ( $saved_token!=$token || $saved_id!=$Wiki_page_id )
        {
            // error; they don't match
            $this->_index_new_page($Wiki_page_id, $instance_id, '', 'Error in request - unable to delete page.');
            return;
        }
        else
        {
            // else; continue
            
            // delete through library
            if ( $this->Wiki->DeletePage($Wiki_page_id) )
            {
                // all ok!
                $this->index($instance_id, 'Page was deleted.'); // delete is called with ajax so it won't affect the url
                return;
            }
            else
            {
                // something went wrong..
                $message = $this->Wiki->GetLastError();
                $this->_index_new_page($Wiki_page_id, $instance_id, '', $message);
                return;
            }
        } 
    }
    
    /**
    * Sets a new code for delete in session
    * (delete-url will fail without correct code)
    */
    private function _GenerateDeleteCode($Wiki_page_id)
    {
        // generate start position 
        $start_pos = -1;
        while ( $start_pos < 0 ) 
        {
            $start_pos = (rand(1,32)-12);    
        }
        // create token
        $token = substr(md5(uniqid().$_SERVER['REMOTE_ADDR']), $start_pos, 12);
        
        // set data
        $this->session->set_userdata('delete_token', $token);
        $this->session->set_userdata('delete_id', $Wiki_page_id);
        
        // return token
        return $token;
    }
    
    //
    // search wiki by word or tag. send 'none'
    // to the parameter that's not used. if both
    // parameters is empty then the searchform is
    // displayed.
    //
    function search($instance_id)
    {
        $word = (isset($_POST['word']) ? $this->input->post('word',true) : '');
        $tag = (isset($_POST['tag']) ? $this->input->post('tag',true) : '');
        
        // search or show form?
        if ($word == '' && $tag == '')
        {
            // show form
            $this->load->view_widget('search', array('instance_id'=>$instance_id));
        }
        else
        {
            // do search 
            $results = null;
            $term = '';
            if ($word != '' && $tag == '')
            {
                $term = $word;
                $results = $this->Wiki->SearchByWord($word, $instance_id);    
            }
            else if ($word == '' && $tag != '')
            {
                $term = $tag;
                $results = $this->Wiki->SearchByTag($tag, $instance_id);    
            }
            

            //  present results
            $this->load->view_widget('search_results', array('results'=>$results,'term'=>$term, 'instance_id'=>$instance_id));

        }    
    }
    
    /**
    * Used for returning an error just as it is with
    * javascript (for example if "not authorized" occurs
    * in index where the whole layout is loaded)
    * 
    * @param string $title
    * @param string $message
    * @return string
    */
    private function _GetDisplayError($title, $message)
    {
        $base_url = $this->config->item('base_url');
        $erroricon = $base_url.'images/backgrounds/erroricon.png';
        
        $returnData = "<h1>$title</h1><span style=\"float:left;margin:5px;margin-top:-10px;\"><img src=\"$erroricon\" /></span>$message";
    
        return $returnData;
    }
    
    /**
    * Show an upload-form
    */
    function upload($instance_id) {
        
        $base_url = $this->config->item('base_url');
        $widget_name = $this->_widget_name;
        
        $viewData = array(
            'instance_id' => $instance_id,
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            'base_url' => $base_url
        );
        
        $this->load->view_widget('upload_form',$viewData);    
    }
    
    /**
    * Process upload
    */
    function do_upload($instance_id) {
        
        // use library since path to upload is stored there
        $result = $this->Wiki->processUpload($instance_id);
        
        // handle result
        if ( $result === false ) {
           
            // something went wrong
            $data = array(
                'instance_id' => $instance_id,
                'error' => $this->Wiki->GetLastError()
            );
            
            $this->load->view_widget('upload_form', $data);  
            
        } else {
            
           // all ok
            $data = array(
                'instance_id' => $instance_id,
                'result' => $result
            );
            
           $this->load->view_widget('upload_success', $data);
           
        }
        
    }
    
    /**
    * Delete an uploaded image
    * 
    */
    function delete_image() {
        
         // check delete token
         if ( $this->Wiki->checkMD5Token() )    {
             
            // delete with library since path to upload is stored there  
            if ( $this->Wiki->deleteImage() )    {
                
                echo 'Ok';
                
             } else {
                 
                 echo 'Error';
                 
             }  
              
         } else {
             
             echo 'Error';
             
         }
        
    }
    
}
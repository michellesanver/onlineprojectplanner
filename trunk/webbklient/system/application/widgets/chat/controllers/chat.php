<?php

class Chat extends Controller {

    function __construct()
    {
        parent::Controller();

        $this->load->library_widget('cashe_lib', null, 'chat');
    }

    /**
    * First function to be called if not specified in URL (Codeigniter)
    * -
    * -
    */

    function index()
    {
        $widget_name = "chat";

        $base_url = $this->config->item('base_url');

        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/"
        );

        $this->load->view_widget('start', $data);
        
    }

    /**
    * Test function to be called for cashe test
    * -
    * -
    */

    function CasheTest()
    {
        $widget_name = "chat";

        $base_url = $this->config->item('base_url');

        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/"
        );

        $this->load->view_widget('cashetest', $data);

    }
  
}

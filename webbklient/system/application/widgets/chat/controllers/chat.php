<?php

class Chat extends Controller {

    function __construct()
    {
        parent::Controller();

        $this->load->library_widget('Cashe_lib', null, 'cashe_lib');
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

        // TEST FEED START

        $cashe = $this->cashe_lib->ReadCashe('cashe_test');

        // TEST FEED END

        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            'cashe' => $cashe
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

        $key = 'cashe_test';
        $message = $key;

        if($this->cashe_lib->WriteCashe($key, $message) != false)
        {
            $result = 'Cashe was written to xml...';

            $cashe = $this->cashe_lib->ReadCashe($key);

            if($cashe != NULL)
            {
                $result = 'Cashe found...';
            }
            else
            {
                $result = 'No cashe found...';
            }
        }
        else
        {
            $result = 'No cashe was written to xml...';

            $cashe = NULL;
        }

        $data = array(
            'base_url' => $base_url,
            'widget_url' => site_url("/widget/$widget_name").'/',
            'widget_base_url' => $base_url."system/application/widgets/$widget_name/",
            'result' => $result,
            'cashe' => $cashe
        );

        $this->load->view_widget('cashetest', $data);

    }
  
}

<?php
 
Class Cashe_lib
{

    private $_CI = null;

    function __construct()
    {
        $this->_CI = & get_instance();

        $this->_CI->load->model_widget('cashe_model', 'Cashe_model');
    }

    /**
    * Used to read cashe
    * -
    * -
    */

    function ReadCashe()
    {
        return 'cashed data';
    }

    /**
    * Used to write cashe
    * -
    * -
    */

    function WriteCashe()
    {
        //
    }

}
  
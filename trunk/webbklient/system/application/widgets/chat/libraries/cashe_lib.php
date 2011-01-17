<?php
 
Class Cashe_lib
{

    private $_CI = null;

    function __construct()
    {
        $this->_CI = & get_instance();

        $this->_CI->load->model_widget("cashe_model", "cashe_model");
    }

    /**
    * Used to read cashe
    * -
    * -
    */

    function ReadCashe($key)
    {
        $cashe = $this->_CI->cashe_model->ReadCashe($key);

        if($cashe != false)
        {
            return $cashe;
        }

        return false;
    }

    /**
    * Used to read latest cashe
    * -
    * -
    */

    function ReadLatestCashe($key, $updated)
    {
        $cashe = $this->_CI->cashe_model->ReadLatestCashe($key, $updated);

        if($cashe != false)
        {
            return $cashe;
        }

        return false;
    }

    /**
    * Used to write cashe
    * -
    * -
    */

    function WriteCashe($key, $currentMessage)
    {
        $currentUserInformation = $this->_CI->user->getLoggedInUser();

        if($currentUserInformation != NULL)
        {
            $currentUser = $currentUserInformation["Username"];
            $currentId = $currentUserInformation["User_id"];
            $currentDateTime = date("Y-m-d H:i:s");

            $cashe = $this->_CI->cashe_model->WriteCashe($key, $currentUser, $currentId, $currentMessage, $currentDateTime);

            if($cashe != false)
            {
                return true;
            }
        }

        return false;
    }

}
  
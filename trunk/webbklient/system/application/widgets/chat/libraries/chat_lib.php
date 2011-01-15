<?php
 
Class Chat_lib
{

    private $_CI = null;
    private $_currentProjectId;
    private $_currentUserId;

    function __construct()
    {
        $this->_CI = & get_instance();

        $this->_CI->load->model_widget("chat_model", "chat_model");
        $this->_CI->load->library("Project_lib", null, "project");

        $this->_currentProjectId = $this->_CI->project->checkCurrentProject();
        $this->_currentUserId = $this->_CI->session->userdata("UserID");
    }

    /**
    * Used to read members
    * -
    * -
    */

    function GetMembersByProjectId()
    {
        // Fetch members

        $members = $this->_CI->chat_model->GetMembersByProjectId($this->_currentProjectId);
        $membersWithInfo = array();

        // Add information about logged in user

        if($members != NULL)
        {
            foreach($members as $member) {

                if($member["User_id"] == $this->_currentUserId)
                {
                    $member["IsLoggedInUser"] = true;
                    array_push($membersWithInfo, $member);
                }
                else
                {
                    $member["IsLoggedInUser"] = false;
                    array_push($membersWithInfo, $member);
                }

            }
        }

        if($membersWithInfo != NULL)
        {
            return $membersWithInfo;
        }

        return false;
    }

    /**
    * Used to register a new chat room
    * -
    * -
    */

    function RegisterNewChatRoom($title)
    {
        // Create Key

        $key = date("Y-m-d h:i:s");

        for($n = 0; $n < 10; $n++)
        {
            switch(rand(1,3))
            {
                // Numbers

                case 1: $key .= chr(rand(49,57));
                break;

                // Lowecase letters

                case 2: $key .= chr(rand(65,90));
                break;

                // Uppercase letters

                case 3: $key .= chr(rand(97,122));
                break;
            }
        }

        // Encrypt (hash) key

        $encryptedKey = md5('mychatroom'.$key);

        // Create insert array

        $insert = array(
            "Key" => $encryptedKey,
            "Title" => $title,
            "Project_id" => $this->_currentProjectId
            );

        $result = $this->_CI->chat_model->RegisterNewChatRoom($insert);

        if($result)
        {
            return true;
        }

        return false;
    }

    /**
    * Used to read chat rooms
    * -
    * -
    */

    function GetChatRoomsByProjectId()
    {
        // Fetch rooms

        $rooms = $this->_CI->chat_model->GetChatRoomsByProjectId($this->_currentProjectId);

        if($rooms != NULL)
        {
            return $rooms;
        }

        return false;
    }

}
  
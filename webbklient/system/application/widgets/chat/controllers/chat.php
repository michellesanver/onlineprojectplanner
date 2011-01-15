<?php

class Chat extends Controller {

    function __construct()
    {
        parent::Controller();

        $this->load->library_widget("Cashe_lib", null, "cashe_lib");
        $this->load->library_widget("Chat_lib", null, "chat_lib");
        $this->load->library("validation");
    }

    /**
    * First function to be called if not specified in URL (Codeigniter)
    * -
    * -
    */

    function index()
    {
        $rooms = $this->chat_lib->GetChatRoomsByProjectId();
        $members = $this->chat_lib->GetMembersByProjectId();

        $data = array(
            "rooms" => $rooms,
            "members" => $members
        );

        $this->load->view_widget("start", $data);
    }

    /**
    * Function to be called for new chat room registration
    * -
    * -
    */

    function RegisterNewChatRoom()
    {
        // Rules

        $rules = array (
            "chat_createnewdiscussionstitle" => "required|max_length[200]|xss_clean"
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "chat_createnewdiscussionstitle" => "Title"
        );

        $this->validation->set_fields($field);

        $status = $this->validation->run();

        $resultStatus = NULL;
        $resultMessages = array();
        $data = array();

        // If have status

        if($status != false)
        {
            // If success

            if($this->chat_lib->RegisterNewChatRoom($this->validation->chat_createnewdiscussionstitle) != false)
            {
                $resultStatus = "ok";
                array_push($resultMessages, "Registration was successful!");
            }
            else
            {
                $resultStatus = "error";
                array_push($resultMessages, "Registration failed!");
            }
        }

        // If no status but post

        if($status == false && isset($_POST["chat_createnewdiscussionsbutton"])) {

            $resultStatus = "error";
            array_push($resultMessages, "Registration failed!");
        }

        $data = array(
            "status" => $resultStatus,
            "messages" => $resultMessages
        );

        $this->load->view_widget("resultview", $data);
    }

    /**
    * Function to be called for chat room reload
    * -
    * -
    */

    function ReloadChatRooms()
    {
        $rooms = $this->chat_lib->GetChatRoomsByProjectId();
        $resultStatus = NULL;
        $resultMessages = array();
        $data = array();

        // If have rooms

        if($rooms != false)
        {
            $resultStatus = "ok";
            array_push($resultMessages, "Reload was successful!");
        }
        else
        {
            $resultStatus = "error";
            array_push($resultMessages, "Reload failed!");
        }

        $data = array(
            "status" => $resultStatus,
            "messages" => $resultMessages,
            "rooms" => $rooms
        );

        $this->load->view_widget("resultview", $data);
    }

}

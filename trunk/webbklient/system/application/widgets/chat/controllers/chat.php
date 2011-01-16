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

    /**
    * Function to be called for cashe of new item
    * -
    * -
    */

    function CasheNewItem()
    {
        // Rules

        $rules = array (
            "chat_postchatitemkey" => "required|max_length[32]",
            "chat_postchatitemmessage" => "required|max_length[300]|xss_clean"
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "chat_postchatitemkey" => "Key",
            "chat_postchatitemmessage" => "Message"
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

            if($this->cashe_lib->WriteCashe($this->validation->chat_postchatitemkey, $this->validation->chat_postchatitemmessage) != false)
            {
                $resultStatus = "ok";
                array_push($resultMessages, "Cashe was successful!");
            }
            else
            {
                $resultStatus = "error";
                array_push($resultMessages, "Cashe failed!");
            }
        }

        // If no status but post

        if($status == false && isset($_POST["chat_createnewdiscussionsbutton"])) {

            $resultStatus = "error";
            array_push($resultMessages, "Cashe failed!");
        }

        $data = array(
            "status" => $resultStatus,
            "messages" => $resultMessages
        );

        $this->load->view_widget("resultview", $data);
    }

    /**
    * Function to be called for cashe load
    * -
    * -
    */

    function LoadCashe()
    {
        // Rules

        $rules = array (
            "chat_loadcashekey" => "required|max_length[32]"
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "chat_loadcashekey" => "Key"
        );

        $this->validation->set_fields($field);

        $status = $this->validation->run();

        $cashe = NULL;
        $resultStatus = NULL;
        $resultMessages = array();
        $data = array();

        // If have status

        if($status != false)
        {
            $cashe = $this->cashe_lib->ReadCashe($this->validation->chat_loadcashekey);

            // If success

            if($cashe != false)
            {
                $resultStatus = "ok";
                array_push($resultMessages, "Load was successful!");
            }
            else
            {
                $resultStatus = "error";
                array_push($resultMessages, "Load failed!");
            }
        }

        // If no status but post

        if($status == false && isset($_POST["chat_loadcashekey"])) {

            $resultStatus = "error";
            array_push($resultMessages, "Load failed!");
        }

        $data = array(
            "status" => $resultStatus,
            "messages" => $resultMessages,
            "cashe" => $cashe
        );

        $this->load->view_widget("resultview", $data);
    }

    /**
    * Function to be called for cashe reload
    * -
    * -
    */

    function ReloadCashe()
    {
        // Rules

        $rules = array (
            "chat_reloadcashekey" => "required|max_length[32]",
            "chat_reloadcasheupdated" => "required"
        );

        $this->validation->set_rules($rules);

        // Human names for the inputfields

        $field = array(
            "chat_reloadcashekey" => "Key",
            "chat_reloadcasheupdated" => "Updated"
        );

        $this->validation->set_fields($field);

        $status = $this->validation->run();

        $cashe = NULL;
        $resultStatus = NULL;
        $resultMessages = array();
        $data = array();

        // If have status

        if($status != false)
        {
            $cashe = $this->cashe_lib->ReadLatestCashe($this->validation->chat_reloadcashekey, $this->validation->chat_reloadcasheupdated);

            // If success

            if($cashe != false)
            {
                $resultStatus = "ok";
                array_push($resultMessages, "Reload was successful!");
            }
            else
            {
                $resultStatus = "error";
                array_push($resultMessages, "Reload failed!");
            }
        }

        // If no status but post

        if($status == false && isset($_POST["chat_reloadcashekey"])) {

            $resultStatus = "error";
            array_push($resultMessages, "Reload failed!");
        }

        $data = array(
            "status" => $resultStatus,
            "messages" => $resultMessages,
            "cashe" => $cashe
        );

        $this->load->view_widget("resultview", $data);
    }

}

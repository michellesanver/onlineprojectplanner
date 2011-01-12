<?php
 
Class Chat_lib
{

    private $_CI = null;

    function __construct()
    {
        $this->_CI = & get_instance();

        $this->_CI->load->model_widget('chat_model', 'chat_model');
        $this->_CI->load->library('Project_lib', null, 'project');
    }

    /**
    * Used to read members
    * -
    * -
    */

    function GetMembersByProjectId()
    {
        // Fetch Project_id

        $projectId = $this->_CI->project->checkCurrentProject();

        // Fetch User_id

        $userId = $this->_CI->session->userdata('UserID');

        // Fetch members

        $members = $this->_CI->chat_model->GetMembersByProjectId($projectId);
        $membersWithInfo = array();

        // Add information about logged in user

        foreach($members as $member) {

            if($member['User_id'] == $userId)
            {
                $member['IsLoggedInUser'] = true;
                array_push($membersWithInfo, $member);
            }
            else
            {
                $member['IsLoggedInUser'] = false;
                array_push($membersWithInfo, $member);
            }

        }
        if($membersWithInfo != null)
        {
            return $membersWithInfo;
        }

        return false;

    }

}
  
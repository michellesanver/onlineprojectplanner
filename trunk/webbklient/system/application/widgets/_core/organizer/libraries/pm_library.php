<?php 

class pm_library {

	/**
	* Function: GetMembersByProjectId
	* This function is used in order to get all members of a project.
	* Adds information about which member is our logged in user
	*
	* @param string $projectId
	* @return mixed
	*/

	function GetMembersByProjectId($projectID) {
		//init data
    $CI = & get_instance();
		$CI->load->model_widget("pm_model");
		
		// Fetch userID
		$userID = $this->_CI->session->userdata('UserID');

		// Fetch members
		$projectMembers = $this->_CI->project_member_model->getByProjectId($projectID);
		$projectMembersWithInfo = array();

		// Add information about logged in user
		foreach($projectMembers as $projectMember) {
			if($projectMember['User_id'] == $userID) {
				$projectMember['IsLoggedInUser'] = true;
				array_push($projectMembersWithInfo, $projectMember);
			} else {
				$projectMember['IsLoggedInUser'] = false;
				array_push($projectMembersWithInfo, $projectMember);
			}
		}
		if($projectMembersWithInfo != null)
		{
			return $projectMembersWithInfo;
		}
		
		return false;
	}
}

?>
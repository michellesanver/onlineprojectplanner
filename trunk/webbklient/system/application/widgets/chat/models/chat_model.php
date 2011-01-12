<?php
 
Class Chat_model extends Model
{

    private $_table_project_member = "Project_Member";
    private $_table_user = "User";
    private $_table_project_role = "Project_Role";

    /**
    * Used to read members
    * -
    * -
    */

    function GetMembersByProjectId($projectId)
    {
        $table1 = $this->_table_project_member;
        $table2 = $this->_table_user;
        $table3 = $this->_table_project_role;

        $this->db->select("$table1.*, $table2.*, $table3.Role");
        $this->db->where(array('Project_id' => $projectId));
        $this->db->from($table1);
        $this->db->join($table2, "$table1.User_id = $table2.User_id");
        $this->db->join($table3, "$table1.Project_role_id = $table3.Project_role_id");

        $query = $this->db->get();

        if($query && $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
        else
        {
            return null;
        }
    }

}
  
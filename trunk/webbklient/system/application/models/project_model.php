<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the library Project
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Project_model extends Model
{

    private $_table = "Project";
    private $_table2 = "Project_Member";

    private function _preCommit()
    {
        $this->db->trans_begin();
        $this->db->trans_commit();
    }

    /**
    * Function: Query_project
    * Query if the projects title exists.
    * Returns false or the row from the database.
    *
    * @param string $title
    * @return mixed
    */

    function Query_project($title)
    {
        // Run query

        $this->db->where('Title', $title);
        $this->db->limit(1);
        $query = $this->db->get($this->_table);

        // Any result?

        if($query->num_rows() > 0) {

            return $query->row(0);
        }
        else {

            // Title not found

            return false;
        }
    }

    /**
    * Function: getById
    * This function will return an array
    * that represents the selected row in the database.
    *
    * @return array
    */

    function getById($projectID)
    {
        $query = $this->db->get_where($this->_table, array('Project_id' => $projectID));
        $res = $query->result_array();
				
				if(count($res) == 1)
					return $res[0];
				else
					return null;
    }

    /**
    * Function: getAll
    * This function will return an array of arrays
    * that represents the rows in the database.
    *
    * @return array
    */

    function getAll()
    {
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    /**
    * Function: Insert_project
    * This will insert the information to the database
    * Insert parameter can be an array or a object of stdClass.
    *
    * @param mixed $insert
    * @return int
    */

    function insert($insert, $userID, $role)
    {
        $res = false;

        // start a transaction; all or nothing

        $this->db->trans_begin();

        // insert new project

        $this->db->insert($this->_table, $insert);

        // nothing changed?

        if ( $this->db->affected_rows() == 0 )
        {
            // roll back transaction and return false
            $this->db->trans_rollback();
            return false;
        }

        $projectID = $this->db->insert_id();

        $res = $this->db->insert($this->_table2, array('User_id' => $userID, 'Project_id' => $projectID, 'Project_role_id' => $role->Project_role_id));

        // was row inserted?

        if ( $res == false )
        {
            // roll back transaction and return false
            $this->db->trans_rollback();
            return false;
        }

        // else; all ok! commit transaction and return true
        $this->db->trans_commit();
        return true;
    }

    /**
    * Function: update
    * Used to send the validated information to the Project_model,
    * which will update the row in the database.
    *
    * @param array $insert
    * @return bool
    */

    function update($update)
    {
        $this->_preCommit();
        $this->db->where('Project_id', $update['Project_id']);
        return $this->db->update($this->_table, $update);
    }

    /**
    * Function: delete
    * Used to send the validated information
    * which will delete the row in the database.
    *
    * @param int $projectID
    * @return bool
    */
    function delete($projectID)
    {
        return $this->db->delete($this->_table, array('Project_id' => $projectID));
    }

}

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
    * Function: Select_project
    * This function will return an array
    * that represents the selected row in the database.
    *
    * @return array
    */

    function Select_project($projectID)
    {
        $res = "";
        $query = $this->db->get_where($this->_table, array('ProjectID' => $projectID));

        foreach ($query->result() as $row)
        {
            $res['ProjectID'] = $row->ProjectID;
            $res['Title'] = $row->Title;
            $res['Description'] = $row->Description;
            $res['Created'] = $row->Created;
        }

        return $res;
    }

    /**
    * Function: Select_all_projects
    * This function will return an array of arrays
    * that represents the rows in the database.
    *
    * @return array
    */

    function Select_all_projects()
    {
        $query = $this->db->get($this->_table);
        $ret = array();

        // Fetches the data from the rows

        foreach($query->result() as $row) {

            $ret[] = array(
            "ProjectID" => $row->ProjectID,
            "Title" => $row->Title,
            "Description" => $row->Description,
            "Created" => $row->Created
            );
        }

        return $ret;
    }

    /**
    * Function: Insert_project
    * Used to send the validated information to the Project_model,
    * which will insert it as a new row in the database.
    * Insert parameter can be an array or a object of stdClass.
    *
    * @param mixed $insert
    * @return bool
    */

    function Insert_project($insert)
    {
        $this->db->insert($this->_table, $insert);
        return $this->db->insert_id();
    }

    /**
    * Function: Update_project
    * Used to send the validated information to the Project_model,
    * which will update the row in the database.
    *
    * @param array $insert
    * @return bool
    */

    function Update_project($update)
    {
        $this->db->where('ProjectID', $update['ProjectID']);
        return $this->db->update($this->_table, $update);
    }

}

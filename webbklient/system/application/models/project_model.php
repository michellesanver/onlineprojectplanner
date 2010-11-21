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

}

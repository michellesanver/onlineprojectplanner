<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table Project_Role
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Project_role_model extends Model
{
    private $_table = "Project_Role";

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
    * Function: getByRole
    * This function will return an array representing
    * the role information of the selected role.
    *
    * @param int $role
    * @return mixed
    */
    function getByRole($role)
    {

        $this->db->where('Role', $role);
        $this->db->limit(1);
        $query = $this->db->get($this->_table);
        
        if($query->num_rows() > 0) {

            return $query->row(0);
        }
        else {

            // Role not found

            return false;
        }

    }

}
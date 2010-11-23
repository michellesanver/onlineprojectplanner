<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table User_resetpassword
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Reset_model extends Model 
{
    
    private $tableName2 = "User_ResetPassword";
    private $tableName = "User"; 
    
    /**
    * Save confirmation code for a user to
    * reset password
    * 
    * @param db $result (CI db-object with user information)
    * @param string $code
    * @return bool
    */
    function SaveConfirmationCode($result, $code)
    {
        // start a transaction; all or nothing
        $this->db->trans_begin();
        
        // create data to be inserted
        $data = array(
            'Resetcode' => $code,
            'User_id' => $result->User_id,
            'Firstname' => $result->Firstname,
            'Lastname' => $result->Lastname,
            'Email' => $result->Email,
            'Username' => $result->Username,
            'Streetadress' => $result->Streetadress,
            'Postalcode' => $result->Postalcode,
            'Hometown' => $result->Hometown
        );
        
        // insert into another table
        $this->db->insert($this->tableName2, $data);
        
        // nothing changed?
        if ( $this->db->affected_rows() == 0 )
        {
            // roll back transaction and return false
            $this->db->trans_rollback();
            return false;
        }
         
        // delete old row in table User
        $res = $this->db->delete($this->tableName, array('User_id' => $result->User_id));
        
        // was row deleted?
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
    * If send confirmation email fails.. then re-insert
    * the user into the correct table and delete from
    * the temporary table.
    * 
    * @param db $result (CI db-object with user information)
    */
    function RollbackUser($result)
    {
        $this->db->insert($this->tableName, $result);
        $this->db->delete($this->tableName2, array('Email'=>$result->Email));     
    }
    
    /**
    *  This will move the user to the correct table
    *  and also set new password.
    * 
    * @param db $result (CI db-object with user information)
    * @param string $new_password (encrypted)
    * @return bool
    */
    function MoveAndUpdateUser($result, $new_password)
    {
    // start a transaction; all or nothing
        $this->db->trans_begin();
        
        // create data to be inserted
        $data = array(
            'Password' => $new_password,
            'User_id' => $result->User_id,
            'Firstname' => $result->Firstname,
            'Lastname' => $result->Lastname,
            'Email' => $result->Email,
            'Username' => $result->Username,
            'Streetadress' => $result->Streetadress,
            'Postalcode' => $result->Postalcode,
            'Hometown' => $result->Hometown
        );
        
        // insert into another table
        $this->db->insert($this->tableName, $data);
        
        // nothing changed?
        if ( $this->db->affected_rows() == 0 )
        {
            // roll back transaction and return false
            $this->db->trans_rollback();
            return false;
        }
         
        // delete old row in table User
        $res = $this->db->delete($this->tableName2, array('User_id' => $result->User_id));
        
        // was row deleted?
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
    * Checks if confirmation code is correct for user. Returns
    * false or if correct, the row for the user.
    * 
    * @param int $uid
    * @param int $code
    * @return mixed
    */
    function CheckConfirmationCode($uid, $code)
    {
        // run query
        $this->db->where('User_id', $uid);     
        $this->db->where('Resetcode', $code);     
        $this->db->limit(1);
        $query = $this->db->get($this->tableName2);
       
        // any result?
        if ($query->num_rows() > 0)
            // return row
            return $query->row(0);
        else
            // user not found
            return false; 
    }
}
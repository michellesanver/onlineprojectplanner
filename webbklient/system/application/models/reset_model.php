<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class has all database-logic for
* the table User_resetpassword
*
* @link https://code.google.com/p/onlineprojectplanner/
*/

class Reset_model extends Model 
{
    
    private $tableName2 = "UserResetPassword";
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
            'Reset_code' => $code,
            'UserID' => $result->UserID,
            'First_name' => $result->First_name,
            'Last_name' => $result->Last_name,
            'Email' => $result->Email,
            'User_name' => $result->User_name,
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
        $res = $this->db->delete($this->tableName, array('UserID' => $result->UserID));
        
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
            'UserID' => $result->UserID,
            'First_name' => $result->First_name,
            'Last_name' => $result->Last_name,
            'Email' => $result->Email,
            'User_name' => $result->User_name,
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
        $res = $this->db->delete($this->tableName2, array('UserID' => $result->UserID));
        
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
        $this->db->where('UserID', $uid);     
        $this->db->where('Reset_code', $code);     
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
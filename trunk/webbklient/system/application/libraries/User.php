<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about users including admin.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class User
{ 
	private $_CI = null;
    private $_last_error = "";
    private $_min_password_length = 8; // minimun length when generating a new password from scratch
    
	function __construct()
	{
		// get CI instance
		$this->_CI = & get_instance();
		
		// load model for library
		$this->_CI->load->model(array('User_model', 'Login_model', 'Activation_model', 'Reset_model'));
	}
	
    /**
    * This function will return the last error
    * this class has set.
    */
    function GetLastError()
    {
        // save error, clear message and return
        $returnStr = $this->_last_error;    
        $this->_last_error = "";
        return $returnStr;
    }
    
    /**
		* Function: ActivateUser
    * This function will activate a user by serching for a codematch
		* and removes the activationcode for that user in the database. 
    * 
    * @param string $code
    * @return bool
    */
		function ActivateUser($code)
		{
			// Fetches the activation row
			$activ = $this->_CI->Activation_model->getWithCode($code);
			
			if(isset($activ) && $activ != null) {
				// Remove the activation row
				if($this->_CI->Activation_model->delete($activ['Activation_id'])) {
					return true;
				}
			}
			return false;
		}
		
    /**
	* Function: Reset_password
    * This function will search if the user exists, create a confirmation code
    * and then send a confirmation email to the user. The confirmation code is
    * also saved to the database. On error false is returned with a message that
    * can be fetched with GetLastError()
    * 
    * @param string $email
    * @param string $username
    * @return bool
    */
    function ResetPassword($email, $username)
    {
        // error in parameters  ?
        if ($email == "" && $username == "" )
        {
            $this->_last_error = "Please enter email or username";
            return false;        
        }
        
        // does the user exist?
        $result = $this->_CI->User_model->QueryUser($email, $username);
        if ( $result == false )
        {
            $this->_last_error = "User was not found";
            return false;  
        }
        
        // generate confirmation code
        $code = "";
        for($n=0; $n<8; $n++) $code .= rand(1,9);
        
        // save confirmation code (will also move the user to another table)
        if ( $this->_CI->Reset_model->SaveConfirmationCode($result, $code) == false )
        {
            $this->_last_error = "Unable to save confirmation code";
            return false;  
        }
        
        // fetch values for email
        $uid = $result->UserID;
        $name = $result->First_name." ".$result->Last_name;
        $email = $result->Email;
        
        // Send an email with confirmation code
        if( $this->_CI->emailsender->SendResetPasswordMail($name, $email, $code, $uid) == false )
        {
            // re-insert user into correct table
            $this->_CI->Reset_model->RollbackUser($result);
            
            // set message
            $this->_last_error = "Unable to send email with confirmation code";
            return false; 
        }
        
        // else; all ok!
        return true;
    }
    
    
    /**
    * Confirm code for user, generate a new password and save to database.
    * Also send new password to email for user. Will return false on error
    * or new password.
    * 
    * @param int $uid
    * @param int $code
    * @return mixed
    */
    function ConfirmResetPassword($uid, $code)
    {
        // error in parameters  ?
        if ($uid == "" && $code == "" )
        {
            $this->_last_error = "User id and code is required";
            return false;        
        }  
       
        // query if user id and code is correct
        $result = $this->_CI->Reset_model->CheckConfirmationCode($uid, $code);
        if ( $result == false )
        {
            $this->_last_error = "Unable to verify confirmation";
            return false;        
        }
        
        // generate new password
        list($new_password_plaintext, $new_password_encrypted) = $this->_createPassword();
        
        // save new password and move user to correct table
        if ( $this->_CI->Reset_model->MoveAndUpdateUser($result, $new_password_encrypted) == false )
        {
            $this->_last_error = "Unable to save new password";
            return false;
        }

        // fetch name and email
        $name = $result->First_name." ".$result->Last_name;
        $email = $result->Email;
        
        // Send an email with new password
        if( $this->_CI->emailsender->SendNewPasswordEmail($name, $email, $new_password_plaintext) == false )
        {
            $this->_last_error = "Unable to send email with new password, make sure you save the new password: $new_password_plaintext";
            return false; 
        }
        
        
        // all ok! return password as plaintext so that it can be displayed
        return $new_password_plaintext;
    }
    
    /**
    * This function will encrypt a password if
    * it is sent to parameter 1, or create a new
    * password and encrypt the generated. The function returns
    * an array with both plaintext and encrypted password.
    * 
    * @param string $password (optional)
    * @return array (plaintext, encrypted)
    */
    private function _createPassword($password="")
    {
        $encrypted = "";
        
        // create new password?
        if (empty($password))    
        {
            $len = $this->_min_password_length;
            for($n=0; $n<$len; $n++)    
            {
                switch (rand(1,3))
                {
                    // numbers
                    case 1: $password .= chr( rand(49,57) ); break;
                            
                    // lowercase letter
                    case 2: $password .= chr( rand(65,90) ); break;
                    
                    // uppercase letter
                    case 3: $password .= chr( rand(97,122) ); break;   
                }
            }
        }
        
        // encrypt (hash) password
        $salt = $this->_CI->config->item('password_salt', 'webclient');
        $encrypted = md5($password.$salt);
        
        // return result
        return array($password, $encrypted);
    }

    function transformPassword($pass)
	{
		$ret = $this->_createPassword($pass);
		return $ret[1];
	}




		/**
		* Function: Register
		* This function will diliver the validated registration
		* information to the user_model.
		* 
		* @param array $insert
		* @param string $key
		* @return bool
		*/
	function Register($insert, $key)
	{
		// encrypt password (sent from crontroller)
		list($plain, $encrypted) = $this->_createPassword($insert['Password']);
		$insert['Password'] = $encrypted;
		
		// insert
		$userID = $this->_CI->User_model->insert($insert);
		if($userID > 0) {
		
			$insert = array(
				"Activation_id" => $userID,
				"Code" => $key,
				"Created" => time()
			);
			if($this->_CI->Activation_model->insert($insert)) {
				return true;
			}
		}
		return false;
	}
	
		/**
		* Function: checkIfExist
		* This function is used in the formvalidation. Searches the 
		* database for a match and returns the answer as an bool.
		* 
		* @param string $column
		* @param string $value
		* @return bool
		*/
	function checkIfExist($column, $value)
	{
		// Fetches all the users
		$users = $this->_CI->User_model->getAll();
		
		// Looping the users to find a match
		foreach($users as $user) {
		
			// Search for match
			if($user[$column] == $value) {
				return true;
			}
		}
		return false;
	}
	
	function login($username, $password) {
		$login = $this->_CI->Login_model->Login($username, $password);
		return($login);
	}

}

?>
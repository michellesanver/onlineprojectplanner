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
		$this->_CI->load->model('User_model');
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
			// Fetches all the users
			$users = $this->_CI->User_model->select_all_users();
			
			// Looping the users to find a match
			foreach($users as $user) {
				if($user['Activation_code'] == $code) {
					
					// Assing the matching user
					$activateUser = $user;
				}
			}
			
			if(isset($activateUser)) {
				
				// Changes the activationcode to null
				$activateUser['Activation_code'] = NULL;
				if($this->_CI->User_model->update_user($activateUser)) {
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
    function Reset_password($email, $username)
    {
        // error in parameters  ?
        if ($email == "" && $username == "" )
        {
            $this->_last_error = "Please enter email or username";
            return false;        
        }
        
        // does the user exist?
        $result = $this->_CI->User_model->query_user($email, $username);
        if ( $result == false )
        {
            $this->_last_error = "User was not found";
            return false;  
        }
         
        // fetch UserID and name
        $uid = $result->UserID;
        $name = $result->First_name." ".$result->Last_name;
        $email = $result->Email;
        
        // generate confirmation code
        $code = "";
        for($n=0; $n<8; $n++) $code .= rand(1,9);
        
        // save confirmation code
        if ( $this->_CI->User_model->save_confirmation_code($uid, $code) == false )
        {
            $this->_last_error = "Unable to save confirmation code";
            return false;  
        }
        
        // prepare email to send
        $system_email = $this->_CI->config->item('system_email', 'webclient');
        $system_email_name = $this->_CI->config->item('system_email_name', 'webclient');
        $email_template = $this->_CI->config->item('reset_password_template', 'webclient');
        $confirm_url = $this->_CI->config->item('confirm_reset_url', 'webclient');
        $subject = $this->_CI->config->item('reset_password_template_subject', 'webclient');
        
        $confirm_url = sprintf(site_url().$confirm_url, $uid,$code);
        $email_template = sprintf($email_template, $name, $confirm_url, $system_email_name);

        // user CI library email
        $this->_CI->load->library('email');
        
        $this->_CI->email->from($system_email, $system_email_name);
        $this->_CI->email->to($email); 
        $this->_CI->email->subject($subject);
        $this->_CI->email->message($email_template); 
        
        // send
        if ( $this->_CI->email->send() == false )
        {
            // failed to send..
            $this->_last_error = "Unable to send email with confirmation";
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
    function Confirm_reset_password($uid, $code)
    {
        // error in parameters  ?
        if ($uid == "" && $code == "" )
        {
            $this->_last_error = "User id and code is required";
            return false;        
        }  
       
        // query if user id and code is correct
        $result = $this->_CI->User_model->check_confirmation_code($uid, $code);
        if ( $result == false )
        {
            $this->_last_error = "Unable to verify confirmation";
            return false;        
        }
        
        // generate new password
        list($new_password_plaintext, $new_password_encrypted) = $this->_createPassword();
        
        // save new password
        if ( $this->_CI->User_model->update_password($uid, $new_password_encrypted, true) == false )
        {
            $this->_last_error = "Unable to save new password";
            return false;
        }
        
        // fetch name
        $name = $result->First_name." ".$result->Last_name;
        $email = $result->Email;
        
        // email new password to user
        $system_email = $this->_CI->config->item('system_email', 'webclient');
        $system_email_name = $this->_CI->config->item('system_email_name', 'webclient');
        $email_template = $this->_CI->config->item('new_password_template', 'webclient');
        $subject = $this->_CI->config->item('new_password_template_subject', 'webclient');
        
        $email_template = sprintf($email_template, $name, $new_password_plaintext, $system_email_name);

        // user CI library email
        $this->_CI->load->library('email');
        
        $this->_CI->email->from($system_email, $system_email_name);
        $this->_CI->email->to($email); 
        $this->_CI->email->subject($subject);
        $this->_CI->email->message($email_template); 
        
        // send
        if ( $this->_CI->email->send() == false )
        {
            // failed to send..
            $this->_last_error = "Unable to send email with new password";
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
    
		/**
		* Function: Register
		* This function will diliver the validated registration
		* information to the user_model.
		* 
		* @param array $insert
		* @return bool
		*/
	function Register($insert)
	{
		return $this->_CI->User_model->insert_user($insert);
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
		$users = $this->_CI->User_model->select_all_users();
		
		// Looping the users to find a match
		foreach($users as $user) {
		
			// Search for match
			if($user[$column] == $value) {
				return true;
			}
		}
		return false;
	}

}

?>
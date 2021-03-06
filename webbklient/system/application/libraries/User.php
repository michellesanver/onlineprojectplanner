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
	private $_passwordSalt = "";
    
	function __construct()
	{
		// from CI or external from install?
		if (function_exists('get_instance')) {
		
			// get CI instance
			$this->_CI = & get_instance();
			
			// load model for library
			$this->_CI->load->model(array('User_model', 'Activation_model', 'Reset_model'));
			
			// get salt for when creating password
			$this->_passwordSalt = $this->_CI->config->item('password_salt', 'webclient');
			
		} else {
			// manually load config to get password salt
			
			require_once dirname(__FILE__).'/../config/webclient.php';
			$this->_passwordSalt = $config['webclient']['password_salt'];
			
		}
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
        // add a tracemessage to log
        log_message('debug','#### => Library User->ActivateUser');
        
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
        // add a tracemessage to log
        log_message('debug','#### => Library User->ResetPassword');
        
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
		$uid = $result->User_id;
        $name = $result->Firstname." ".$result->Lastname;
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
        // add a tracemessage to log
        log_message('debug','#### => Library User->ConfirmResetPassword');
        
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
        $name = $result->Firstname." ".$result->Lastname;
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
        $encrypted = md5($password.$this->_passwordSalt);
        
        // return result
        return array($password, $encrypted);
    }
	
	/**
	 * Transforms a password into the encrypted version.
	 * @param $pass
	 * @return string
	 */
	function TransformPassword($pass)
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
	* @return mixed
	*/
	function Register($insert, $key)
	{
        // add a tracemessage to log
        log_message('debug','#### => Library User->Register');
        
		// encrypt password (sent from crontroller)
		list($plain, $encrypted) = $this->_createPassword($insert['Password']);
		$insert['Password'] = $encrypted;
		
		$this->_CI->db->trans_begin();
		
		// insert
		$userID = $this->_CI->User_model->insert($insert);
		
		if($userID <= 0 || $this->_CI->db->affected_rows() == 0) {
			// Rollback transaction and return false
			$this->_CI->db->trans_rollback();
			return false;
		}
		
		$insert = array(
			"Activation_id" => $userID,
			"Code" => $key,
		);
		
		$res = $this->_CI->Activation_model->insert($insert);
		
		if($res == false) {
			// Rollback transaction and return false
			$this->_CI->db->trans_rollback();
			return false;
		}
		
		$this->_CI->db->trans_commit();
		return $userID;
	}
	
	/**
    * Check if a user is online or offline
    * 
    * @return bool
    */
	function IsLoggedIn()
	{
        // add a tracemessage to log
        log_message('debug','#### => Library User->IsLoggedIn');
        
        $isloggedin = $this->_CI->session->userdata('login_status');

        if( $isloggedin === false )
        {	
		    $this->_CI->session->set_userdata('login_status', 'offline'); 
			return false;
		}
		if($isloggedin == "online"){	
				return true;
		}
		if($isloggedin != "online"){
			return false;
		}
		return false;
	}
	
	/**
    * 
    * Check if a user has activated his account by cklicking the
		* link in the activation email. The $input can either be 
		* User_id or Username.
    * 
		* @param mixed $input
    * @return bool
    */
	function IsActivated($input)
	{
        // add a tracemessage to log
        log_message('debug','#### => Library User->IsActivated');
        
		$user = null;
		
		// Gets the user.
		if(is_int($input)) {
			$user = $this->_CI->User_model->getById($input);
		} else if(is_string($input)) {
			$user = $this->_CI->User_model->getByUsername($input);
		}
		
		// If the person is not registered yet
		if($user == null) {
			$this->_last_error = "You are not registered!";
			return false;
		}
		
		// Gets the activation row.
		$activation = $this->_CI->Activation_model->getById($user['User_id']);
		
		// If the user has clicked the activationlink it has then been removed from the database 
		if($activation == null) {
			return true;
		} else {
			$this->_last_error = "You are not yet activated!";
			return false;
		}
	}
	
	/**
    * 
    * Do logout and kill session
    * 
    * @return bool
    */
	function Logout()
	{	
        // add a tracemessage to log
        log_message('debug','#### => Library User->Logout');
        
		$this->_CI->session->sess_destroy();
		return true;
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
	
	/**
	 * Logs in a user if credentials is correct.
	 * 
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	function Login($username, $password)
    {
        // add a tracemessage to log
        log_message('debug','#### => Library User->Login');
        
		$encryptedpassword = $this->TransformPassword($password);
		$login = $this->_CI->User_model->checkLogin($username, $encryptedpassword);
		if($login != false) {
			$this->_CI->session->set_userdata($login);
			return true;
		} else {
			return false;
		}
		
		}
    
    /**
    * Returns userID for the logged in user.
    * If not logged in false is returned.
    * 
    * @return mixed
    */
    function getUserID()
    {
        if ( $this->IsLoggedIn() )
            // UserID is set in user_model->checkLogin
            return $this->_CI->session->userdata('UserID');
        else
            return false;
    }
		
    /**
    * Returns the authorized user.
    * 
    * @return mixed
    */
    function getLoggedInUser()
    {
        if ( $this->IsLoggedIn() )
            // UserID is set in user_model->checkLogin
            return $this->_CI->User_model->getById($this->_CI->session->userdata('UserID'));
        else
            return false;
    }
		
    /**
    * Updates a user in the database
    * 
		* @param array $update
    * @return bool
    */
	function updateUser($update)
	{
        // add a tracemessage to log
        log_message('debug','#### => Library User->updateUser');
        
		return $this->_CI->User_model->update($update);
	}
	
    /**
    * Deletes a user from the database
    * 
	* @param int $userid
    * @return bool
    */
	function removeUser($userid)
	{
        // add a tracemessage to log
        log_message('debug','#### => Library User->removeUser');
        
		return $this->_CI->User_model->delete($userid);
	}
}

?>
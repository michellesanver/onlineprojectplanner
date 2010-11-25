<?php

/*
* Class User_controller
*/

class User_controller extends Controller {

	function User_controller()
	{
		parent::Controller();	
		
		$this->load->library(array('validation', 'emailsender'));
	}
	
    /**
    * Function: Reset password
    *
    * Description: Will begin the process to reset password.
    * An email will be sent with a confirmation-code first.
    * 
    * If $UserID and $code is sent then confirm code and
    * create a new password.
    * 
    * @param int $UserID
    * @param int $code
    */
    function Reset_password($UserID='', $code='')
    {
        // show form or reset password?
        if ( empty($UserID) && empty($code) )
        {
            // ---------------------
            // show form
        
            $formData = array(); 
            
            /*
            * Rules for the inputfields
            */
            $rules = array(
                "email" => "trim|max_length[100]|valid_email|xss_clean",
                "username" => "trim|max_length[100]|xss_clean"
            );   
           $this->validation->set_rules($rules);  
           
           // do validation
           $status = $this->validation->run(); 
          
            // is validation ok?
           if ($status)
           { 
                // fetch post data and filter with xss_clean
                $email = (isset($this->validation->email) ? $this->validation->email : '');
                $username = (isset($this->validation->username) ? $this->validation->username : '');
                
                //  is username or email set?
                if ( empty($email) && empty($username) )
                {
                    // no, show error
                    $formData = array(
                                    "status" => "error",
                                    "status_message" => "Error(s): Please enter email or username"
                    );
                }
                else
                {
                    // begin reset process in user library    
                    if( $this->user->ResetPassword($email, $username) )
                    {
                    
                        // all ok!    
                        $formData = array(
                            "status" => "ok",
                            "status_message" => "Information has been sent to your email to reset password"
                         );
                            
                    }
                    else
                    {
                        
                        // something went wrong...    
                        $formData = array(
                            "status" => "error",
                            "status_message" => "Error(s): ".$this->user->GetLastError()
                         );
                         
                    }
                    
                }
           }
           else
           {
               // any validation error?
               $errors = validation_errors();
               if ( empty($errors) == false || empty($this->validation->error_string) == false )
               {
                   
                    // set error
                    $formData = array(
                        "status" => "error",
                        "status_message" => 'Error(s): '.strip_tags($errors.$this->validation->error_string)
                     );
                           
               }
           }
           
           // should we re-populate form?
           if ( isset($formData['status']) && $formData['status'] == 'error')
           {
                if (isset($this->validation->email)) $formData['email'] = $this->validation->email;
                if (isset($this->validation->username)) $formData['username'] = $this->validation->username;
           }
           
        }
        else
        {
            // ---------------------
            // reset password
            
            
            // reset process in user library    
            $new_password = $this->user->ConfirmResetPassword($UserID, $code);
            if( $new_password != false )
            {
            
                // all ok!    
                $formData = array(
                    "status" => "ok",
                    "status_message" => "Password reset successful! New password: ".$new_password,
                    "hideForm" => true
                 );
                    
            }
            else
            {
                // something went wrong...    
                $formData = array(
                    "status" => "error",
                    "status_message" => "Error(s): ".$this->user->GetLastError()
                 );
            }
            
        }
       
       // show view with pre and post content
       $this->theme->view('user/reset_password', $formData); 
    }
    
    /**
     *
     */
     function Login()
     {
     	if(isset($_POST)) {
     		if($this->user->Login($_POST["username"], $_POST["password"]) == true) {
     			echo("Inloggad!");
     		} else {
     			echo("Inte inloggad");
     		}
     	}
     	
     	$this->theme->view('user/login');
     	
     }
	/**
	* Function: Register
	* 
	* Description: Will show the user/register.php view and
	* catch the formvalues if the submit button is clicked.
	*/
	function Register()
	{
		/*
		* Rules for the inputfields
		*/
		$rules = array(
			"firstname" => "trim|required|max_length[100]|alpha|xss_clean",
			"lastname" => "trim|required|max_length[100]|alpha|xss_clean",
			"email" => "trim|required|max_length[100]|xss_clean|valid_email|callback_email_check",
			"username" => "trim|required|max_length[100]|xss_clean|callback_username_check",
			"password" => "trim|required|max_length[32]|xss_clean|matches[password2]|md5",
			"password2" => "trim|required|max_length[32]|xss_clean",
			"streetadress" => "trim|max_length[100]|xss_clean",
			"postalcode" => "trim|max_length[5]|integer",
			"hometown" => "trim|max_length[130]|xss_clean"
		);
		
		$this->validation->set_rules($rules);
		
		/*
		* Human names for the inputfields
		*/
		$field = array(
			"firstname" => "Firstname",
			"lastname" => "Lastname",
			"email" => "Email",
			"username" => "Username",
			"password" => "Password",
			"password2" => "Repeat password",
			"streetadress" => "Streetadress",
			"postalcode" => "Postalcode",
			"hometown" => "Hometown"
		);
		
		$this->validation->set_fields($field);    
		
		$status = $this->validation->run();
		
		$data = array();
		
		if($status) {
			$insert = array(
				"Firstname" => $this->validation->firstname,
				"Lastname" => $this->validation->lastname,
				"Email" => $this->validation->email,
				"Username" => $this->validation->username,
				"Password" => $this->validation->password,
				"Streetadress" => $this->validation->streetadress,
				"Postalcode" => $this->validation->postalcode,
				"Hometown" => $this->validation->hometown
			);
			
			//Generates a random activation code
			$key = "";
			for($i = 0; $i < 5; $i++) {
				$key .= rand(1,9);
			}
			$key = md5($key);
			
			/*
			*If validation is ok => send to library
			*/
			if($this->user->Register($insert, $key))
            {	
				// Sends an activationemail
				if ( $this->emailsender->SendActivationMail($insert['Firstname'], $insert['Email'], $key) == false)
                {
                    $data = array(
                        "status" => "error",
                        "status_message" => "Failed to send activation email"
                    );
                }
                else
                {
                    // all ok
                    $data = array(
                        "status" => "ok",
                        "status_message" => "Registration was successful!"
                    );
                }
			}
            else
            {
                // registration failed
                $status = false;   
            }
		}
		
        // re-populate form if error
		if($status === false && isset($_POST['register_btn'])) {
			$data = array(
				"firstname" => $this->validation->firstname,
				"lastname" => $this->validation->lastname,
				"email" => $this->validation->email,
				"username" => $this->validation->username,
				"streetadress" => $this->validation->streetadress,
				"postalcode" => $this->validation->postalcode,
				"hometown" => $this->validation->hometown,
				"status" => "error",
				"status_message" => "Registration failed!"
			);
		}
		
		$this->theme->view('user/register', $data);
	}
	
	/**
	* Function: email_check
	* This function is part of the register validation. It will stop any
	* registration with an email that already exist
	* 
	*@param string $str
	*@return bool
	*/
	function email_check($str)
	{
		if($this->user->checkIfExist("Email", $str) == true) {
			$this->validation->set_message('email_check', 'That emailadress already exist in our database.');
			return false;
		}
		return true;
	}
	
	/**
	* Function: username_check
	* This function is part of the register validation. It will stop any
	* registration with an username that already exist
	* 
	*@param string $str
	*@return bool
	*/
	function username_check($str)
	{   
		if($this->user->checkIfExist("Username", $str)) {
			$this->validation->set_message('username_check', 'That username already exist in our database.');
			return false;
		}
		return true;
	}
	
    
	/**
	* Function: Activate
	* This function will catch the third section of the uri
	* and activate the user who has that activationcode.
	* Will redirect the klient to the homepage if the uri is'nt
	* valid.
	*/
	function Activate()
	{
		if($this->uri->segment(3) != "") {
			if($this->user->ActivateUser(trim($this->uri->segment(3)))) {
				$this->theme->view('user/activated');
			} else {
				$this->theme->view('user/notactivated');
			}
		} else {
			redirect("","");
		}
	}
	
	function RecommendNewUser()
	{
		//TODO: Check if user is authorized
		
		/*
		* Rules for the inputfields
		*/
		$rules = array(
			"recEmail" => "trim|required|xss_clean|valid_email"
		);
		$this->validation->set_rules($rules);
		
		/*
		* Human names for the inputfields
		*/
		$field = array(
			"recEmail" => "Email"
		);
		$this->validation->set_fields($field);
		
		$status = $this->validation->run();
		
		$data = array();
		
		if($status) {
			$insert = array(
				"recEmail" => $this->validation->recEmail
			);
			
			// TODO: Catch userinformation who sends the recomendation
			$firstName = "Not";
			$lastName = "Implemented";
			$name = $firstName . " " . $lastName;
			
			// Sends an activationemail
			if($this->emailsender->SendRecommendationMail($name, $insert['recEmail'])) {
				$data = array(
					"status" => "ok",
					"status_message" => "The recommendation was sent!"
				);
			}
            else {
                // failed
                $status = false;
            }
		}
		
		if($status == false && isset($_POST['recSubmit'])) {
			$data = array(
				"recEmail" => $this->validation->recEmail,
				"status" => "error",
				"status_message" => "Failed to send!"
			);
		}
		
		$this->theme->view('user/recommend', $data);
	}
}

/* End of file user_controller.php */
/* Location: ./system/application/controllers/user_controller.php */
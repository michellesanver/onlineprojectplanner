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
                "email" => "trim|max_length[100]|valid_email",
                "username" => "trim|max_length[100]|xss_clean"
            );   
           $this->validation->set_rules($rules);  
           
           // do validation
           $status = $this->validation->run(); 
            
            // is validation ok?
           if ($status)
           { 
                //  is username or email set?
                if ( empty($this->validation->email) && empty($this->validation->username) )
                {
                    // no, show error
                    $formData = array(
                                    "status" => "error",
                                    "status_message" => "Please enter email or username"
                    );
                }
                else
                {
                    // fetch post data
                    $email = (isset($this->validation->email) ? $this->validation->email : "");
                    $username = (isset($this->validation->username) ? $this->validation->username : "");
                    
                    // begin reset process in user library    
                    if( $this->user->Reset_password($email, $username) )
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
                            "status_message" => $this->user->GetLastError()
                         );
                    }
                    
                }
           }
        }
        else
        {
            // ---------------------
            // reset password
            
            
            // reset process in user library    
            $new_password = $this->user->Confirm_reset_password($UserID, $code);
            if( $new_password != false )
            {
            
                // all ok!    
                $formData = array(
                    "status" => "ok",
                    "status_message" => "Password reset successful! New password: ".$new_password
                 );
                    
            }
            else
            {
                // something went wrong...    
                $formData = array(
                    "status" => "error",
                    "status_message" => $this->user->GetLastError()
                 );
            }
            
        }
       
       // show view
       $this->load->view('user/reset_password', $formData); 
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
			"first_name" => "trim|required|max_length[100]|alpha|xss_clean",
			"last_name" => "trim|required|max_length[100]|alpha|xss_clean",
			"email" => "trim|required|max_length[100]|valid_email",
			"username" => "trim|required|max_length[100]|xss_clean",
			"password" => "trim|required|max_length[32]|matches[password2]|md5",
			"password2" => "trim|required|max_length[32]|xss_clean",
			"streetadress" => "trim|required|max_length[100]|xss_clean",
			"postalcode" => "trim|required|max_length[5]|integer",
			"hometown" => "trim|required|max_length[130]|xss_clean"
		);
		
		$this->validation->set_rules($rules);
		
		/*
		* Human names for the inputfields
		*/
		$field = array(
			"first_name" => "Firstname",
			"last_name" => "Lastname",
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
			//Generates a random activation code
			$key = "";
			for($i = 0; $i < 5; $i++) {
				$key .= rand(1,9);
			}
			$key = md5($key);
			
			$insert = array(
				"First_name" => $this->validation->first_name,
				"Last_name" => $this->validation->last_name,
				"Email" => $this->validation->email,
				"User_name" => $this->validation->username,
				"Password" => $this->validation->password,
				"Streetadress" => $this->validation->streetadress,
				"Postalcode" => $this->validation->postalcode,
				"Hometown" => $this->validation->hometown,
				"Activation_code" => $key
			);
			
			/*
			*If validation is ok => send to library
			*/
			if($this->user->Register($insert)) {
				$data = array(
					"status" => "ok",
					"status_message" => "Registration was successful!"
				);
				
				// Sends an activationemail
				$this->emailsender->SendActivationMail($insert['First_name'], $insert['Email'], $insert['Activation_code']);
			}
		}
		
		if($status == false && isset($_POST['register_btn'])) {
			$data = array(
				"first_name" => $this->validation->first_name,
				"last_name" => $this->validation->last_name,
				"email" => $this->validation->email,
				"username" => $this->validation->username,
				"streetadress" => $this->validation->streetadress,
				"postalcode" => $this->validation->postalcode,
				"hometown" => $this->validation->hometown,
				"status" => "error",
				"status_message" => "Registration failed!"
			);
		}
		
		$this->load->view('user/register', $data);
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
				$this->load->view('user/activated');
			} else {
				$this->load->view('user/notactivated');
			}
		} else {
			redirect("","");
		}
	}
}

/* End of file user_controller.php */
/* Location: ./system/application/controllers/user_controller.php */
<?php

class User_controller extends Controller {

	function User_controller()
	{
		parent::Controller();	
		
		$this->load->library(array('validation'));
	}
	
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
			$insert = array(
				"First_name" => $this->validation->first_name,
				"Last_name" => $this->validation->last_name,
				"Email" => $this->validation->email,
				"Username" => $this->validation->username,
				"Password" => $this->validation->password,
				"Streetadress" => $this->validation->streetadress,
				"Postalcode" => $this->validation->postalcode,
				"Hometown" => $this->validation->hometown
			);
			
			if($this->user->Register($insert)) {
				$data = array(
					"status" => "ok",
					"status_message" => "Registration was successful!"
				);
			}
		}
		
		if(!$status && isset($_POST['register_btn'])) {
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
	
}

/* End of file user_controller.php */
/* Location: ./system/application/controllers/user.php */
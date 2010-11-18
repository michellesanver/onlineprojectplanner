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
		$rules['first_name'] = "trim|require|max_length[100]|alpha|xss_clean";
		$rules['last_name'] = "trim|require|max_length[100]|alpha|xss_clean";
		$rules['email'] = "trim|require|max_length[100]|valid_email";
		$rules['username'] = "trim|require|max_length[100]|xss_clean";
		$rules['password'] = "trim|require|max_length[32]|matches[password2]|md5";
		$rules['password2'] = "trim|require|max_length[32]|xss_clean";
		$rules['streetadress'] = "trim|require|max_length[100]|xss_clean";
		$rules['postalcode'] = "trim|require|max_length[5]|integer";
		$rules['hometown'] = "trim|require|max_length[130]|xss_clean";
		
		$this->validation->set_rules($rules);
		
		/*
		* Human names for the inputfields
		*/
		$field['first_name'] = "Firstname";
		$field['last_name'] = "Lastname";
		$field['email'] = "Email";
		$field['username'] = "Username";
		$field['password'] = "Password";
		$field['password2'] = "Repeat password";
		$field['streetadress'] = "Streetadress";
		$field['postalcode'] = "Postalcode";
		$field['hometown'] = "Hometown";
		
		$this->validation->set_fields($field);
		
		$status = $this->validation->run();
		
		if($status) {
			$insert['first_name'] = $this->validation->first_name;
			$insert['last_name'] = $this->validation->last_name;
			$insert['email'] = $this->validation->email;
			$insert['username'] = $this->validation->username;
			$insert['password'] = $this->validation->password;
			$insert['streetadress'] = $this->validation->streetadress;
			$insert['postalcode'] = $this->validation->postalcode;
			$insert['hometown'] = $this->validation->hometown;
			
			if($this->User->Register($insert)) {
			}
		}
		
		$data = array();
		if(!$status && isset($_POST['register_btn'])) {
			$data['first_name'] = $this->validation->first_name;
			$data['last_name'] = $this->validation->last_name;
			$data['email'] = $this->validation->email;
			$data['username'] = $this->validation->username;
			$data['streetadress'] = $this->validation->streetadress;
			$data['postalcode'] = $this->validation->postalcode;
			$data['hometown'] = $this->validation->hometown;
			$data['status'] = "error";
			$data['status_message'] = "Registration failed!";
		}
		
		$this->load->view('user/register', $data);
	}
	
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */
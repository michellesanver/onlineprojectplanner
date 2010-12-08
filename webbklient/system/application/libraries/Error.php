<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This class handles all functions
* about error.
*
* @link https://code.google.com/p/onlineprojectplanner/
*/
class Error
{ 
	private $_CI = null;
  private $_last_error = "";
    
	function __construct()
	{
		// get CI instance
		$this->_CI = & get_instance();
		
		// load model for library
		$this->_CI->load->model(array('Error_model'));
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
	* Return the full log from the database
	* 
	* @return array
	*/
	function GetFullLog()
	{
		return $this->_CI->Error_model->getAll();
	}
		
    /**
    * Will log an errormessage and inserts it to the database
    * 
    * @param string $message
    * @param string $ip
    * @param string $function
    * @param array $var
    * @return mixed
    */
    function Log($message, $ip, $function, $calling, $var="")
    {
			$insert = array(
				'Ip_adress' => $ip,
				'Function' => $function,
				'Calling' => $calling,
				'Message' => $message
			);
			
			$var_str = "";
			if($var == "") {
				$var_str = "none";
			} else {
				foreach($var as $key => $val) {
					$var_str .= $key . " => " . $val . "; ";
				}
			}
			$insert['Variables'] = $var_str;
			
			$error_id = $this->_CI->Error_model->insert($insert);
			
			if($error_id > 0) {
				return $error_id;
			} else {
				return false;
			}
    }
		
		function RemoveLogEntity($err_id) {
			return $this->_CI->Error_model->delete($err_id);
		}
}

?>
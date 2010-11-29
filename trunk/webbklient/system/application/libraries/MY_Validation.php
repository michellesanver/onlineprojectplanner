<?php

/**
* This class extends or overrides the base-library Validation
*
* @author Fredrik Johansson <tazzie76@gmail.com>
* @link https://code.google.com/p/onlineprojectplanner/
*/
class MY_Validation extends CI_Validation {

    
    function __construct()
    {
        parent::CI_Validation();
    }
	
    /**
    * Custom validation function so that swedish
    * characters is supported; no spaces or numbers
    * or other special characters
    * 
    * @param string $str
    * @return bool
    */
    function alpha($str)
    {
        $regex = '/^([a-z]|å|ä|ö)+$/i';    
        if ( preg_match($regex, $str) )
            return true;
        else
            return false;
    }
	
    /**
    * Custom validation function so that swedish
    * characters is supported; no spaces or numbers
    * or other special characters
    * 
    * @param string $str
    * @return bool
    */
    function alpha_numeric($str)
    {
        $regex = '/^([a-z0-9]|å|ä|ö)+$/i';    
        if ( preg_match($regex, $str) )
            return true;
        else
            return false;
    }
    
    /**
    * Custom validation function so that swedish
    * characters is supported; no spaces or numbers
    * or other special characters
    * 
    * @param string $str
    * @return bool
    */
    function alpha_dash($str)
    {
        $regex = '/^([a-z0-9_-]|å|ä|ö)+$/i';    
        if ( preg_match($regex, $str) )
            return true;
        else
            return false;
    }
}
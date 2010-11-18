<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');   


/* ---------------------------------------
* Site title
* --------------------------------------- */

$config['webclient']['site_title'] = "Online Project Planner";

/* ---------------------------------------
* Password salt
* --------------------------------------- */

$config['webclient']['password_salt'] = "21j757d73I";


/* ---------------------------------------
* System email (reset password etc)
* - will be used as a "from-address"
* --------------------------------------- */

$config['webclient']['system_email'] = "noreply@".($_SERVER['SERVER_NAME']=='localhost' ? 'mydomain.com' : $_SERVER['SERVER_NAME']); // only localhost will be marked a spam
$config['webclient']['system_email_name'] = "Online Project Planner";


/* ---------------------------------------
* Settings for reset password
* --------------------------------------- */

// url to confirm (first %s is UserID and second %s is confirmation code)
$config['webclient']['confirm_reset_url'] = "/user_controller/reset_password/%s/%s";

// email template for reset password
$config['webclient']['reset_password_template_subject'] = "Reset password";
$config['webclient']['reset_password_template'] = "Hi %s, "."\n\n"."Please follow this link to confirm password reset and to generate a new password"."\n"."%s"."\n\n"."Regards, "."\n"."%s";

// email template for new password
$config['webclient']['new_password_template_subject'] = "New password";
$config['webclient']['new_password_template'] = "Hi %s, "."\n\n"."Here is your new password: %s"."\n\n"."Regards, "."\n"."%s";


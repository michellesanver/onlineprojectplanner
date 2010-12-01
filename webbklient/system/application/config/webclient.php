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
* Theme name (folder in css/.. and views/..)
* - NO ending slash, just the folder name
* --------------------------------------- */

$config['webclient']['theme'] = "default";


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
$config['webclient']['confirm_reset_url'] = "account/resetpassword"; // parameters added in library Emailsender

// email template for reset password
$config['webclient']['reset_password_template_subject'] = "Reset password";
$config['webclient']['reset_password_template'] = "Hi %s, "."\n\n"."Please follow this link to confirm password reset and to generate a new password"."\n"."%s"."\n\n"."Regards, "."\n"."%s";

// email template for new password
$config['webclient']['new_password_template_subject'] = "New password";
$config['webclient']['new_password_template'] = "Hi %s, "."\n\n"."Here is your new password: %s"."\n\n"."Regards, "."\n"."%s";

/* ---------------------------------------
* Settings for activation mail
* --------------------------------------- */
$config['webclient']['activation_template_subject'] = "Activation email";
$config['webclient']['activation_url'] = "account/activate"; // parameters added in library Emailsender
$config['webclient']['activation_template'] = "Dear %s<br />This email is autogenerated because you have tried to register at %s.<br /><br />Click on the link below to activate:<br /><a href=\"%s\">%s</a>";


/* ---------------------------------------
* Settings for recommendation mail
* --------------------------------------- */
$config['webclient']['recommendation_template_subject'] = "Recommendation email from %s";
$config['webclient']['recommendation_template'] = "Hello<br />Your friend %s would like you to join this awesome applikation \"Superwiki\".<br /> You can find more information here, <a href=\"%s\">%s</a>";


<?php

    // define constant
    define('BASEPATH', dirname(__FILE__).'/');

    // get step from parameters or set to 1 as default
    $step = (isset($_GET['step']) ? (int)$_GET['step'] : 1);

    // get config-file
    require_once BASEPATH.'data/settings.php';
    
    // get file for User-library (create password for administrator)
    require_once BASEPATH.'../system/application/libraries/User.php';
    
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title>Online Project Planner :: Install</title>
  <style type="text/css">
        body {
            font-size: 14px;
            font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;
        }
        
        p.error {
            margin: 10px;
            padding: 10px;
            background-color: #e9e9e9;
            color: #ef0000;
            border: 2px solid #a9a9a9;
            width: 625px;
            font-weight: bold;
        }
        
        p.ok {
            margin: 10px;
            padding: 10px;
            background-color: #e9e9e9;
            color: #007f00;
            border: 2px solid #a9a9a9;
            width: 625px;
            font-weight: bold;
        }
  </style>
</head>
<body>
    
<h1>Online Project Planner :: Install</h1>   

<?php

// ###############################################
// -----------------------------------------------

//
// what step?
//

switch ($step) {    
    case 1: step_one(); break;
    case 2: step_two(); break;
    case 3: step_three(); break; 
    default: step_error(); break;
}


// ###############################################
// -----------------------------------------------

function step_one($error='') {

    
    // refill form?
    $dbname = (isset($_POST['dbname']) ? $_POST['dbname'] : '');
    $dbprefix = (isset($_POST['dbprefix']) ? $_POST['dbprefix'] : '');
    $dbhost= (isset($_POST['dbhost']) ? $_POST['dbhost'] : '');
    $dbuser = (isset($_POST['dbuser']) ? $_POST['dbuser'] : '');
    $dbpassword = (isset($_POST['dbpassword']) ? $_POST['dbpassword'] : '');
    $dbtype = (isset($_POST['dbtype']) ? $_POST['dbtype'] : '');
    
    //
    // show form
    //
    ?>    
    <h2>Step 1 of 2: Database</h2>

    <?php if (empty($error) == false): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <p>Please fill in the details in the form below. Depending on your webhost you may have to create the database yourself.</p>
    
    <script type="text/javascript">
        function showProcessing() {
            document.getElementById('processing').style.display = 'inline';
            document.getElementById('submitButton').disabled = true;
            tick.timer = setInterval('tick()', 1000);
        }
        
        function tick() {
            var value = parseInt(document.getElementById('dbtimer').innerHTML);
            if ( value != 0) {
                document.getElementById('dbtimer').innerHTML = value-1;
            } else {
                clearInterval(tick.timer);
            }
        }
    </script>
    
    <form method="post" onsubmit="showProcessing();" action=<?php echo '"'.$_SERVER['PHP_SELF'].'?step=2">'; ?> 
        <table>
            <tr>
                <td>Database name:</td>
                <td><input type="text" name="dbname" size="30" value="<?php echo $dbname; ?>" /></td>
            </tr>
            <tr>
                <td>Hostname:</td>
                <td><input type="text" name="dbhost" size="30" value="<?php echo $dbhost; ?>" /></td>
            </tr>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="dbuser" size="30" value="<?php echo $dbuser; ?>" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="text" name="dbpassword" size="30" value="<?php echo $dbpassword; ?>" /> (optional)</td>
            </tr>
            <tr>
                <td>Type:</td>
                <td>
                        <select name="dbtype">
                            <option value="mysqli" <?php if ( ($dbtype != "" && $dbtype == 'mysqli') || $dbtype == "") { echo  'selected="selected"'; } ?>>MySQLi (recommended)</option>
                            <option value="mysql" <?php if ($dbtype != "" && $dbtype == 'mysql') { echo  'selected="selected"'; } ?>>MySQL</option>
                        </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><br/><input type="submit" value="Proceed" id="submitButton" /><span id="processing" style="display:none;">&nbsp;&nbsp;<img src="images/spinner.gif" width="16" height="16" /> Connecting to database... <span id="dbtimer"><?php echo (int)ini_get('max_execution_time'); ?></span></span></td>
            </tr>
        </table>
    </form>
    
    <?php
}


// ###############################################
// -----------------------------------------------

function step_two($onlyForm=false, $error='') {
    
    $ok_message = "";
    
    // process data or skip to show form?
    if ($onlyForm == false) {
    
        //
        // process data from step one
        //
        
        // get data from post
        $dbname = (isset($_POST['dbname']) ? $_POST['dbname'] : '');
        $dbhost = (isset($_POST['dbhost']) ? $_POST['dbhost'] : '');
        $dbuser = (isset($_POST['dbuser']) ? $_POST['dbuser'] : '');
        $dbpassword = (isset($_POST['dbpassword']) ? $_POST['dbpassword'] : '');
        $dbtype = (isset($_POST['dbtype']) ? $_POST['dbtype'] : '');
        
        // validate
        if ( empty($dbname) || empty($dbhost) || empty($dbuser) || empty($dbtype) ) {
         
            // show error and step one
            step_one('Error: Please fill in all required fields.');
            return;
        
        }
        
        // run helper function
        $result = install_database($dbname, $dbhost, $dbuser, $dbpassword, $dbtype);
        
        // exit this function?
        if ( $result === false ) {
            return;
        }
        
        // else; database was installed
        $ok_message = "Alrighty then! Database was installed with a perfect success.";
    }
    
    // refill form?
    $firstname = (isset($_POST['firstname']) ? $_POST['firstname'] : '');
    $lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : '');
    $email = (isset($_POST['email']) ? $_POST['email'] : '');
    $username = (isset($_POST['username']) ? $_POST['username'] : '');
    $password = (isset($_POST['password']) ? $_POST['password'] : '');
    $password2 = (isset($_POST['password2']) ? $_POST['password2'] : '');
    
    
    //
    // continue to step two
    //
    ?>    
    <h2>Step 2 of 2: Administrator</h2>
    
    <?php if (empty($error) == false): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if (empty($ok_message) == false): ?>
        <p class="ok"><?php echo $ok_message; ?></p>
    <?php endif; ?>
    
    <p>Oh.. one more thing! Please fill in the form below to create the first administrator-account.</p>
    
    <script type="text/javascript">
        function showProcessing() {
            document.getElementById('processing').style.display = 'inline';
            document.getElementById('submitButton').disabled = true;
        }
    </script>
    
    <form method="post" onsubmit="showProcessing();" action=<?php echo '"'.$_SERVER['PHP_SELF'].'?step=3">'; ?>
    <table>
            <tr>
                <td>Firstname:</td>
                <td><input type="text" name="firstname" size="30" value="<?php echo $firstname; ?>" /></td>
            </tr>
            <tr>
                <td>Lastname:</td>
                <td><input type="text" name="lastname" size="30" value="<?php echo $lastname; ?>" /></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" size="30" value="<?php echo $email; ?>" /></td>
            </tr>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" size="30" value="<?php echo $username; ?>" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="text" name="password" size="30" value="<?php echo $password; ?>" /></td>
            </tr>
            <tr>
                <td>Repeat password:</td>
                <td><input type="text" name="password2" size="30" value="<?php echo $password2; ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td><br/><input type="submit" value="Proceed" id="submitButton" /><span id="processing" style="display:none;">&nbsp;&nbsp;<img src="images/spinner.gif" width="16" height="16" /> Hold yer horses for just a second... </span></td>
            </tr>
        </table>
    </form>
    <?php
}

// -----------------------------------------------

// helper to step two
function install_database($dbname, $dbhost, $dbuser, $dbpassword, $dbtype) {

    global $install_config;

    
    //
    // test db-connection
    //
    $db = null;
    if ($dbtype == 'mysqli') {
        
        // supress error
        error_reporting(0);
        
        // connect 
        $db = mysqli_init();
        $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, (int)ini_get('max_execution_time'));
        $db->real_connect($dbhost, $dbuser, $dbpassword);
        
        // reset errorlevel 
        error_reporting(E_ALL ^ E_NOTICE);

        // any error?
        if ($db && empty($db->connect_error)==false) {
            
            // return to step one with error
            step_one('Error '.$db->connect_errno.': '. $db->connect_error);
            return false; 
        
        } else if (empty($db)) {
            
            // return to step one with error
            step_one('Error: Unable to connect to database!');
            return false; 
        } 
        
    } else if ($dbtype == 'mysql') {
        
        if (!ini_get('safe_mode')) {
            set_time_limit(30);   
        }
        
        // connect
        $db = mysql_connect($dbhost, $dbuser, $dbpassword);

        
        // any other error?
        if (!$db || mysql_error()) {
            
            // return to step one with error
            if (mysql_error())
                step_one('Error: Unable to connect to database! '.mysql_error());
            else
                step_one('Error: Unable to connect to database!');
                
            return false;  
        }
        
    }
    
    //
    // check if database exist; or try to create
    //
    if ($dbtype == 'mysqli') { 
    
        // try to select
        $result = $db->select_db($dbname);
        if ( $result === false ) {
         
            // try to create
            $dbname2 = $db->real_escape_string($dbname);
            $db->query('CREATE DATABASE '.$dbname2);
            
            // try to select
            $result = $db->select_db($dbname2);
            if ( $result === false ) {
              
                // return to step one with error
                if ($db->connect_error)
                    step_one('Error '.$db->connect_errno.': '. $db->connect_error);
                else
                    step_one('Error: Unable to create a new database!');
                    
                return false;  
                
            }
            
        }
    
    } else if ($dbtype == 'mysql') {
        
        // try to select
        if ( @mysql_select_db($dbname, $db) == false ) {
            
            // else try to create db
            $dbname2 = mysql_real_escape_string($dbname);
            $result = mysql_query('CREATE DATABASE '.$dbname2, $db);
            
            if ( $result === false ) {
               
                // return to step one with error
                if (mysql_error())
                    step_one('Error: Unable to create a new database! '.mysql_error());
                else
                    step_one('Error: Unable to create a new database!');
                    
                return false;  
                
            } else {
            
                // retry select db
                if ( mysql_select_db($dbname2, $db) == false ) {
                   
                    // return to step one with error
                    if (mysql_error())
                        step_one('Error: Unable to create a new database! '.mysql_error());
                    else
                        step_one('Error: Unable to create a new database!');
                        
                    return false;  
                    
                }
            
            }
            
        }
        
    }
    
    //
    // load sql-file for install and execute it
    //
    
    $sql_data = file(BASEPATH.'data/'.$install_config['sql_file']);
 
    if ($dbtype == 'mysqli') { 
    
        $templine = "";
        
        foreach ($sql_data as $line_num => $line) {
    
            if (substr($line, 0, 2) != '--' && $line != '') {
                $templine .= $line;
                
                if (substr(trim($line), -1, 1) == ';') {
                    $templine = preg_replace('/(\r|\r\n|\n)/','',$templine);
          
                    $result = $db->query( $templine );
                
                    if ( $result === false) {
                        // return to step one with error
                        step_one('Error: Unable to install database! ('.$db->connect_errno.') '. $db->connect_error); 
                        return false; 
                    }
               
                    $templine = '';
                }
            }
        }
    
        // close connection
        $db->close();
    
    } else if ($dbtype == 'mysql') {
        
        $templine = "";
        
        foreach ($sql_data as $line_num => $line) {
    
            if (substr($line, 0, 2) != '--' && $line != '') {
                $templine .= $line;
                
                if (substr(trim($line), -1, 1) == ';') {
                    $templine = preg_replace('/(\r|\r\n|\n)/','',$templine);
       
                    $result = mysql_query( $templine, $db );
                    if ( $result === false) {
                         // return to step one with error
                        if (mysql_error())
                            step_one('Error: Unable to install database! '.mysql_error());
                        else
                            step_one('Error: Unable to install database!');
                            
                        return false;  
                    }
               
                    $templine = '';
                }
            }
        }
        
        // close connection
        mysql_close($db);
    }
    
    
    //
    // save database config
    //
    $tplfile = file_get_contents(BASEPATH.'data/'.$install_config['ci_database_template']);
    
    // insert values
    $tplfile = preg_replace('/{DB_HOSTNAME}/', $dbhost, $tplfile);
    $tplfile = preg_replace('/{DB_DATABASENAME}/', $dbname, $tplfile);
    $tplfile = preg_replace('/{DB_USERNAME}/', $dbuser, $tplfile);
    $tplfile = preg_replace('/{DB_PASSWORD}/', $dbpassword, $tplfile);
    $tplfile = preg_replace('/{DB_TYPE}/', $dbtype, $tplfile);
    
    // save new file
    $destination_filename = BASEPATH."../system/application/config/database.php";
    $result = file_put_contents($destination_filename, $tplfile);
    
    // was save ok?
    if ($result == false || file_exists($destination_filename)==false) {
    
        step_one("Error: Database was installed but settings couldn't be saved.");
        return false; 
    
    }
    
    // save database settings to session for next step
    session_start();
    $_SESSION['dbhost'] = (string)$dbhost;
    $_SESSION['dbname'] = (string)$dbname;
    $_SESSION['dbuser'] = (string)$dbuser;
    $_SESSION['dbpassword'] = (string)$dbpassword;
    $_SESSION['dbtype'] = (string)$dbtype;

    // all ok!
    return true;
}


// ###############################################
// -----------------------------------------------

function step_three() {
  
    // get data from previous step
    session_start(); 
    $dbhost = (string)$_SESSION['dbhost'];
    $dbname = (string)$_SESSION['dbname'];
    $dbuser = (string)$_SESSION['dbuser'];
    $dbpassword = (string)$_SESSION['dbpassword'];
    $dbtype = (string)$_SESSION['dbtype'];
    
    // get data from post
    $firstname = (isset($_POST['firstname']) ? $_POST['firstname'] : '');
    $lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : '');
    $email = (isset($_POST['email']) ? $_POST['email'] : '');
    $username = (isset($_POST['username']) ? $_POST['username'] : '');
    $password = (isset($_POST['password']) ? $_POST['password'] : '');
    $password2 = (isset($_POST['password2']) ? $_POST['password2'] : '');

    // validate as not empty
    if ( empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password) || empty($password2)) {
    
        // show error and step two (only the form)
        step_two(true, 'Error: Please fill in all required fields.');
        return;
    }
    
    // does passwords match?
    if ( $password != $password2) {
    
        // show error and step two (only the form)
        step_two(true, 'Error: Passwords does not match.');
        return;
    }
    
    //
    // Save new administrator
    //
    
    // begin sql for insert
    $sql = "INSERT INTO `User` (`Firstname`, `Lastname`, `Email`,`Password`, `Username`) VALUES ";
    
    if ($dbtype == 'mysqli') {
    
        // supress error
        error_reporting(0);
        
        // connect 
        $db = mysqli_init();
        $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, (int)ini_get('max_execution_time'));
        $db->real_connect($dbhost, $dbuser, $dbpassword);
        
        // reset errorlevel 
        error_reporting(E_ALL ^ E_NOTICE);

        // any error?
        if ($db && empty($db->connect_error)==false) {
            
            // return to step two with error
            step_two(true, 'Error '.$db->connect_errno.': '. $db->connect_error);
            return;
        
        } else if (empty($db)) {
            
            // return to step two with error
            step_two(true, 'Error: Unable to connect to database!');
            return;
        }
    
        // select database
        $result = $db->select_db($dbname);
        if ( $result === false ) {
              
            // return to step two with error
            if ($db->connect_error)
                step_two(true, 'Error '.$db->connect_errno.': '. $db->connect_error);
            else
                step_two(true, 'Error: Unable to select database!');
                
            return; 
        }
    
        // escape data 
        $firstname = $db->real_escape_string($firstname);
        $lastname = $db->real_escape_string($lastname);
        $email = $db->real_escape_string($email);
        $username = $db->real_escape_string($username);
    
        // encrypt password for database with the same library as webclient
        $user = new User();
        $encrypted_password = $user->TransformPassword( $db->real_escape_string($password));
    
        // complete sql
        $sql .= "('$firstname', '$lastname', '$email', '$encrypted_password', '$username');";
    
        // insert 
        $result = $db->query( $sql);
        if ( $result === false) {
            // return to step one with error
            step_two(true, 'Error: Unable to save administrator! ('.$db->connect_errno.') '. $db->connect_error); 
            return; 
        }
    
        // close connection
        $db->close();
    
    
    // -----------------------------
    } else if ($dbtype == 'mysql') {
    
        // connect
        $db = mysql_connect($dbhost, $dbuser, $dbpassword);

        // any other error?
        if (!$db || mysql_error()) {
            
            // return to step one with error
            if (mysql_error())
                step_two(true, 'Error: Unable to connect to database! '.mysql_error());
            else
                step_two(true, 'Error: Unable to connect to database!');
                
            return false;  
        }
    
        // select db
        $dbname2 = mysql_real_escape_string($dbname);
        if ( mysql_select_db($dbname2, $db) == false ) {
           
            // return to step one with error
            if (mysql_error())
                step_two(true, 'Error: Unable to select database! '.mysql_error());
            else
                step_two(true, 'Error: Unable to select database!');
                
            return false;  
            
        }
    
        // escape data 
        $firstname = mysql_real_escape_string($firstname);
        $lastname = mysql_real_escape_string($lastname);
        $email = mysql_real_escape_string($email);
        $username = mysql_real_escape_string($username);
    
        // encrypt password for database with the same library as webclient
        $user = new User();
        $encrypted_password = $user->TransformPassword( mysql_real_escape_string($password));
    
        // complete sql
        $sql .= "('$firstname', '$lastname', '$email', '$encrypted_password', '$username');";
    
        // insert
        $result = mysql_query( $sql, $db );
        if ( $result === false) {
             // return to step one with error
            if (mysql_error())
                step_two(true, 'Error: Unable to save administrator! '.mysql_error());
            else
                step_two(true, 'Error: Unable to save administrator!');
                
            return false;  
        }
    
        // close connection
        mysql_close($db);
    
    }
    
    
    // kill session as we are done
    session_destroy();
    
    //
    // show final result; all done
    //
    ?>    
    <h2>Finished</h2>
    
    <p><strong>All done!</strong> You can now login with your account =) <em>Please delete the folder "install" now as it is a security issue.</em></p>
    
    <?php
}

// ###############################################
// -----------------------------------------------

function step_error() {
    ?>
    
    <h2>Error</h2>
    <p><strong>Ehm.. what step? Maybe start from the beginning?</strong></p>
    
    <?php  
}

?>
    
   
</body>
</html>
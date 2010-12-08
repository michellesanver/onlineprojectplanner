

    <h1>AJAX template; parameter test</h1>
    
    <p>Query is: <?php echo $query; ?></p>
    
    <p><a href="javascript:void(0);" class="small" onclick="ajaxTemplateWidget.load('/some_controller_name');"><< Back to previous page</a></p> 
    
    <br/>
    
    <p>Username: <?php echo $user['Username']; ?></p>
    
    <p>Firstname: <input type="text" value="<?php echo $user['Firstname']; ?>" /><br/>
       Lastname: <input type="text" value="<?php echo $user['Lastname']; ?>" /></p>
    
    <p>Password: <input type="text" value="" /></p> 
       
    <p>Email: <input type="text" value="<?php echo $user['Email']; ?>" /></p>
    
    <p><input type="submit" value="Save" /></p>

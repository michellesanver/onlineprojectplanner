<html>
<head></head>

<body>

    <h1>Iframe template; parameter test</h1>
    
    <p>Query is: <?php echo $query; ?></p>
    
    <p><a href="javascript:window.back(-1);"><< Back to previous page</a></p> 
    
    <p>Username: <?php echo $user['Username']; ?></p>
    
    <p>Firstname: <input type="text" value="<?php echo $user['Firstname']; ?>" /><br/>
       Lastname: <input type="text" value="<?php echo $user['Lastname']; ?>" /></p>
    
    <p>Password: <input type="text" value="" /></p> 
       
    <p>Email: <input type="text" value="<?php echo $user['Email']; ?>" /></p>
    
    <p><input type="submit" value="Save" /></p>
    
</body>
</html>

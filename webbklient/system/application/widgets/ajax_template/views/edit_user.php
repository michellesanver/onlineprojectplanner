     <style type="text/css">
        pre.code {margin-top:20px; margin-left:10px; padding:5px; background-color:#d9d9d9; border:2px dashed #f0f0f0;}
    </style>

<div id="ajax_template_wrapper" style="padding:15px;">

    <h1>AJAX template; parameter test</h1>
    
    <p>Query is: <?php echo $query; ?></p>
    
    <p><a href="javascript:void(0);" class="small" onclick="ajaxTemplateWidget.loadURL('/some_controller_name');"><< Back to previous page</a></p> 
    
    <br/>
    
    <form method="post" action="" onsubmit="return false" class="form1">
        <input type="hidden" id="" name="User_id" value="<?php echo $user['User_id']; ?>" />
        <p>Username: <?php echo $user['Username']; ?></p>
        
        <p>Firstname: <input type="text" id="Firstname" name="Firstname" value="<?php echo $user['Firstname']; ?>" /><br/>
           Lastname: <input type="text" id="Lastname" name="Lastname" value="<?php echo $user['Lastname']; ?>" /></p>
        
        <p>Password: <input type="text" id="Password" name="Password" value="" /></p> 
           
        <p>Email: <input type="text" id="Email" name="Email" value="<?php echo $user['Email']; ?>" /></p>
        
        <p><input type="button" value="Save" onclick="ajaxTemplateWidget.postURL('form1', '/some_controller_name/save_edit_user');" /> (hit save to view an example of post)</p>
    </form>
    
    
    <p><hr /></p>
    
<pre class="code">
    CODE (form post): 
    &lt;form method="post" action="return false;" onsubmit="ajaxTemplateWidget.postURL('form1', '/some_controller_name/save_edit_user');" class="form1"> 
</pre>

</div>
<html>
<head>
<title>Upload Form</title>
</head>
<body>

<h2>Upload a file</h2>
<?php echo (isset($error) ? 'Error(s): '.$error : '');?>

<form action="<?php echo site_url(); ?>/widget/wiki/pages/do_upload/<?php echo $instance_id; ?>" method="post" enctype="multipart/form-data">

<input type="file" name="userfile" size="20" />
<br/><br/>
<input type="submit" value="Upload" />

</form>

</body>
</html>
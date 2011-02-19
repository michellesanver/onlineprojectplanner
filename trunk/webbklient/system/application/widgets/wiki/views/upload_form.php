<html>
<head>
    <title>Upload Form</title>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.5.min.js"></script>
</head>
<body>

<h2>Upload a file</h2>
<?php echo (isset($error) ? 'Error(s): '.$error : '');?>

<form action="<?php echo site_url(); ?>/widget/wiki/pages/do_upload/<?php echo $instance_id; ?>" method="post" enctype="multipart/form-data" onsubmit="$('#frame_uploading').css('display','inline');return true;">

<input type="file" name="userfile" size="20" />
<br/><br/>
<input type="submit" value="Upload" /> <div id='frame_uploading' style="display:none;margin-left:15px;"><img src="<?php echo $widget_base_url; ?>images/spinner.gif" /> Uploading file...</div>
</form>

</body>
</html>
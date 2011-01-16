<html>
<head>
<title>Upload Form</title>
</head>
<body>

<h2>Upload a file</h2> 
<h3>Your file was successfully uploaded!</h3>


<?php echo 'Filename: '.$result['file_name']; ?><br/>
<?php echo 'Filesize: '.$result['file_size'].' Kb'; ?><br/><br/>

<?php echo 'Image width: '.$result['image_width']; ?><br/>
<?php echo 'Image height: '.$result['image_height']; ?>


<p><?php echo anchor(site_url().'/widget/wiki/pages/upload/'.$instance_id, 'Upload another file'); ?></p>


<script type="text/javascript">
    window.parent.wiki_add_uploaded_image('<?php echo $result['file_name']; ?>');
</script>

</body>
</html>
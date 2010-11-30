<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title><?php echo $site_title; ?><?php echo (isset($page_title) ? "- $page_title" : ''); ?></title>
  <link href="<?php echo $base_url . "css/" . $theme_folder . "/style.css"; ?>" rel="Stylesheet" type="text/css" />
  
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.6.custom.min.js"></script>
 
</head>

<body>
	<div id="topbar">
	<?php
		if($this->user->isLoggedIn()) {
			echo("<a href=\"" . site_url('user_controller/logout') . "\"><img class=\"topbuttons\" src=\"{$base_url}images/buttons/logout.png\"/></a>");
		}
	?>
	</div>
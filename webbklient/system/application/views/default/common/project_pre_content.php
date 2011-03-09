<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title><?php echo $site_title; ?><?php echo (isset($page_title) ? "- $page_title" : ''); ?></title>
  
  <!-- site theme -->
  <link href="<?php echo $base_url . "css/" . $theme_folder . "/style.css"; ?>" rel="Stylesheet" type="text/css" />
  
  <!-- common scripts -->
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.5.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.6.complete.min.js"></script>
  <link href="<?php echo $base_url; ?>css/smoothness/jquery.tooltip.css" rel="Stylesheet" type="text/css" />
  <link href="<?php echo $base_url; ?>css/smoothness/jquery-ui-1.8.6.custom.css" rel="Stylesheet" type="text/css" />
  
  <!-- plugins -->
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/window/jquery.window.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.dump.js"></script>
  <link href="<?php echo $base_url; ?>js/window/css/jquery.window.css" rel="Stylesheet" type="text/css" />
  <link href="<?php echo $base_url; ?>js/window/css/jquery.window-opp.css" rel="Stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo $base_url; ?>js/common.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.tooltip.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.dialog-wrappers.js"></script>
  
  <!-- online project planner scripts -->
  <script type="text/javascript" src="<?php echo site_url('project/common_variables'); ?>"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/common.js"></script> 
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/desktop.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/desktop_remote.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/widget.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/widget_remote.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/ajax_requests.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/widgetbar.js"></script>
  
  <!-- widget scripts -->
  <?php echo $widget_javascript; ?>
  <?php echo $widget_css; ?>
</head>

<body>

<div id="wrapper">
    
    <div id="fullpage_overlay"></div><div id="message"></div> 

    <div id="topbar">

        <h1><?php echo "<a href=\"$base_url\" class=\"home_link\">$site_title</a>"; ?> <span>BETA</span></h1>

        <?php if($is_logged_in): ?>

        <div id="topbarbuttonwrapper">

            <div id="topbarmenu">

                <ul>
                    <li><a href="<?php echo(site_url('project/index')); ?>" class="home">Home</a></li>
                    <li><a href="<?php echo(site_url('account/edit')); ?>" class="profile">Profile</a></li>
                    <li><a href="<?php echo(site_url('account/logout')); ?>" class="logout">Logout</a></li>
                </ul>

                <br style="clear:left;" />

            </div>

            <div id="projectdropdown">

                <ul>
                    <?php if(!is_null($current_project_name)): ?>
                        <li class="top"><p><?php echo($current_project_name); ?></p></li>
                    <?php else: ?>
                        <li class="top"><p>Choose project</p></li>
                    <?php endif; ?>

                    <?php foreach($project_list as $id => $name): ?>

                        <?php if($current_project_id != $id): ?>
                            <li class="item"><a href="<?php echo site_url('project/'.$id); ?>"><?php echo($name); ?></a></li>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </ul>

            </div>

            <br style="clear:both;" />

        </div>

        <?php endif; ?>

        <br style="clear:both;" />

    </div>
	
    <div id="widget_bar"><div class="icon_container"><div class="icon_wrapper"></div><br style="clear: left;" /></div></div>

    <div id="desktop" pid="<?php echo $current_project_id; ?>">


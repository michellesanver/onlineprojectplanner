<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title><?php echo $site_title; ?><?php echo (isset($page_title) ? "- $page_title" : ''); ?></title>
  <link href="<?php echo $base_url . "css/" . $theme_folder . "/style.css"; ?>" rel="Stylesheet" type="text/css" />
  <link href="<?php echo $base_url; ?>css/smoothness/jquery.tooltip.css" rel="Stylesheet" type="text/css" />
  
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.5.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.6.complete.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.tooltip.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/index_tooltip.js"></script>
 
</head>

<body>
<div id="wrapper">

    <div id="topbar">

        <h1><?php echo "<a href=\"$base_url\" class=\"home_link\">$site_title</a>"; ?> <span>BETA</span></h1>

        <?php if($is_logged_in): ?>

        <div id="topbarbuttonwrapper">

            <div id="topbarmenu">

                <ul>
                    <li><p><a href="http://www.pppp.nu">Back to pppp.nu!</a></p></li>
                    <li><a href="<?php echo(site_url('project/index')); ?>" class="button home">Home</a></li>
                    <li><a href="<?php echo(site_url('account/edit')); ?>" class="button profile">Profile</a></li>
                    <li><a href="<?php echo(site_url('account/logout')); ?>" class="button logout">Logout</a></li>
                </ul>

                <br style="clear:left;" />

            </div>

            <div id="projectdropdown">

                <ul>
                    <?php if(!is_null($current_project_name)): ?>
                        <?php if(strlen($current_project_name) <= 25): ?>
                        <li class="top"><p><?php echo($current_project_name); ?></p></li>
                        <?php else:
                            $string = $current_project_name;
                            $string = substr($string,0,25);
                            $string = substr($string,0,strrpos($string," "));
                            $string .= '...'
                        ?>
                        <li class="top"><p><?php echo($string); ?></p></li>
                        <?php endif; ?>
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

        <?php else: ?>

        <div id="topbarbuttonwrapper">

            <div id="topbarmenu">

                <ul>
                    <li><p><a href="http://www.pppp.nu">Back to pppp.nu!</a></p></li>
                    <li><a href="<?php echo($base_url); ?>" class="button login">Login</a></li>
                    <li><a href="<?php echo(site_url('account/register')); ?>" class="button register">Register</a></li>
                </ul>

                <br style="clear:left;" />

            </div>

        </div>

        <?php endif; ?>

        <br style="clear:both;" />

    </div>
    
    <div id="content">
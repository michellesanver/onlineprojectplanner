<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title><?php echo $site_title; ?><?php echo (isset($page_title) ? "- $page_title" : ''); ?></title>
  <link href="<?php echo $base_url . "css/" . $theme_folder . "/style.css"; ?>" rel="Stylesheet" type="text/css" />
  <link href="<?php echo $base_url; ?>css/smoothness/jquery-ui-1.8.6.custom.css" rel="Stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.6.complete.min.js"></script>
  <script type="text/javascript" src="<?php echo site_url('project/common_variables'); ?>"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/window/jquery.window.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/common.js"></script> 
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.dump.js"></script>
  <link href="<?php echo $base_url; ?>js/window/css/jquery.window.css" rel="Stylesheet" type="text/css" />
  <link href="<?php echo $base_url; ?>js/window/css/jquery.window-opp.css" rel="Stylesheet" type="text/css" />
  
  <!-- widgets -->
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/desktop.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/widget.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/widget/ajax_requests.js"></script>
  <?php echo $widget_javascript; ?>
  <?php echo $widget_css; ?>
</head>

<body>

<div id="wrapper">
    
    <div id="fullpage_overlay"></div><div id="message"></div> 
  
    <div id="topbar">
        <span class="sitetitle"><?php echo "<a href=\"$base_url\" class=\"home_link\">$site_title</a>"; ?></span>
        
        
        <?php if($is_logged_in): ?>
            
            <div class="topbuttons">
                
                <div id="topbarbuttonwrapper">
				    
                    <div id="projectdropdown">
                        <ul>
                            <?php if(!is_null($current_project_name)): ?>
                                <li class="top"><?php echo($current_project_name); ?></li>
                            <?php else: ?>
                                <li class="top">Choose project</li>
                            <?php endif; ?>
                            
                            <?php foreach($project_list as $id => $name): ?>
                                
                                <?php if($current_project_id != $id): ?>
                                    <li class="item">
                                        <a href="<?php echo site_url('project/'.$id); ?>">
                                            <?php echo($name); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </ul>
                    </div>
    			   
    				<div id="topbarimages">
    				    <a href="<?php echo(site_url('project/index')); ?>">
    				       <img src="<?php echo("{$base_url}images/buttons/home.png"); ?>"/>
    				    </a>
    				    
    				    <a href="<?php echo(site_url('account/edit')); ?>">
    				       <img src="<?php echo("{$base_url}images/buttons/profile.png"); ?>"/>
    				    </a>
    				    
    				    <a href="<?php echo(site_url('account/logout')); ?>">
    				       <img src="<?php echo("{$base_url}images/buttons/logout.png"); ?>"/>
    				    </a>
    				    
    				</div>	
					
					

                </div>
                
                <br style="clear:both;" />
            </div>

        <?php endif; ?>

    
    <div id="widget_bar"><?php echo($widget_bar); ?></div>
    
    <div id="desktop" pid="<?php echo $current_project_id; ?>">


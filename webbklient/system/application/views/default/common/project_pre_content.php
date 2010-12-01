<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title><?php echo $site_title; ?><?php echo (isset($page_title) ? "- $page_title" : ''); ?></title>
  <link href="<?php echo $base_url . "css/" . $theme_folder . "/style.css"; ?>" rel="Stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-ui-1.8.6.custom.min.js"></script>
  <script type="text/javascript" src="<?php echo site_url('project/common_variables'); ?>"></script>
  <?php echo $widget_javascript; ?>
  <?php echo $widget_css; ?>
</head>

<body>
    <div id="topbar">
        <span class="sitetitle"><?php echo "<a href=\"$base_url\" class=\"home_link\">$site_title</a>"; ?></span>
        <?php
            if($is_logged_in) {
                echo "<span class=\"topbuttons\">";
                echo("<a href=\"" . site_url('account/logout') . "\"><img src=\"{$base_url}images/buttons/logout.png\"/></a>");
                echo "</span>";
            }
        ?>
    </div>
    
    <div id="widget_bar"><?php echo $widget_bar; ?> </div>
    
    <div id="content">

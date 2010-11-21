<?php

    function get_site_base_url($index_page='')
    {
        
        // h?mta v?rden
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!="") ? 'https://' : 'http://';
        $server_name = $_SERVER['SERVER_NAME'];
        $port = ($_SERVER['SERVER_PORT']!=80) ? ':'.$_SERVER['SERVER_PORT'] : '';
        $uri = $_SERVER['REQUEST_URI'];
        
        // klippa bort index? (eller vad den heter)
        $index_pos = stripos($uri, $index_page);     
        if ( $index_pos != false )
            $uri = substr($uri, 0, $index_pos);
        
        // bygg ihop resultatet
        $return_url = $protocol.$server_name.$port.$uri;

        // retunera url
        return $return_url;
    }

    function site_url() // wrapper
    {
        return get_site_base_url('index.php');    
    }
    
    define('BASEPATH', dirname(__FILE__));
    
	require_once "widgets.php";
	$widgets = new Widgets();
	
?>
<!DOCTYPE html>

<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
  <title>Widget test</title>
  <link href="css/style.css" rel="Stylesheet" type="text/css"/>
  <script type="test/javascript" src="js/jquery-1.4.4.min.js"></script>
  <script type="test/javascript" src="js/window/jquery.window.js"></script>
  <?php $widgets->printWidgetJavascripts(); ?>
  <?php $widgets->printWidgetStylesheets(); ?>
</head>

<body>


<?php $widgets->printIcons(); ?> 



</body>
</html>
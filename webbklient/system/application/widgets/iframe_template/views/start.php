<html>
<head>
    <style type="text/css">
        pre.code {margin-top:20px; margin-left:10px; padding:5px; background-color:#d9d9d9; border:2px dashed #f0f0f0;}
    </style>
</head>

<body>

    <h1>Iframe template</h1>

    <p>This Iframe template uses the style <strong>Iframe</strong> in jquery window.</p>
    <p>This view is loaded from the subfolder 'views' inside the folder for the widget.</p>
    
    <p><a href="<?php echo $widget_url; ?>main/show_documentation">show jquery.window documentation</a></p>



    <h2>Data sent to view</h2>

    <p><strong>base_url:</strong><br /><?php echo $base_url; ?></p>

    <p><strong>widget_url:</strong><br /><?php echo $widget_url; ?></p>

    <p><strong>widget_base_url:</strong><br /><?php echo $widget_base_url; ?></p>  



    <h2>Examples</h2>
    
<pre class="code"> 
CODE (Call to a function inside the namespace):
    &lt;p>&lt;a href="javascript:parent.iframeTemplateWidget.example_showMessage('Hello World');">Call to a function inside the namespace&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:parent.iframeTemplateWidget.example_showMessage('Hello World');">Call to a function inside the namespace</a></p>

<pre class="code"> 
CODE (Call to a global function - error):
    &lt;p>&lt;a href="javascript:parent.show_errormessage('This is a error message');">Call to a global function - error&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:parent.show_errormessage('This is a error message');">Call to a global function - error</a></p>  

<pre class="code"> 
CODE (database-model test):
    &lt;p>&lt;a href="&lt;?php echo $widget_url; ?>main/model_test">database-model test&lt;/a>&lt;/p>
</pre>
    <p><a href="<?php echo $widget_url; ?>main/model_test">database-model test</a></p>  

<pre class="code"> 
CODE (library test):
    &lt;p>&lt;a href="&lt;?php echo $widget_url; ?>main/library_test">library test&lt;/a>&lt;/p>
</pre>    
    <p><a href="<?php echo $widget_url; ?>main/library_test">library test</a></p>    

<pre class="code"> 
CODE (parameter test):
    &lt;p>&lt;a href="&lt;?php echo $widget_url; ?>main/edit_user/&lt;?php echo $userID; ?>">parameter test&lt;/a>&lt;/p>
</pre>
    
    <p><a href="<?php echo $widget_url; ?>main/edit_user/<?php echo $userID; ?>">parameter test</a></p>

<pre class="code">
CODE (Image from widget-folder):
    &lt;p>&lt;img src="&lt;?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" />&lt;/p>
</pre>
    
    <p><img src="<?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" /></p>

</body>
</html>

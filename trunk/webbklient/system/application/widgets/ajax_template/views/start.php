<style type="text/css">
	pre.code {margin-top:20px; margin-left:10px; padding:5px; background-color:#d9d9d9; border:2px dashed #f0f0f0;}
</style>

<h1>AJAX template</h1>
<p>This AJAX template uses the style <strong>AJAX</strong> in jquery window.</p>
<p>This view is loaded from the subfolder 'views' inside the folder for the widget.</p>
<p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/show_documentation');">show jquery.window documentation</a></p>


<h2>Data sent to view</h2>
<p><strong>base_url:</strong><br /><?php echo $base_url; ?></p>
<p><strong>widget_url:</strong><br /><?php echo $widget_url; ?></p>
<p><strong>widget_base_url:</strong><br /><?php echo $widget_base_url; ?></p>  


<h2>Examples</h2>

<pre class="code"> 
CODE (Call to a function inside the namespace):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'helloWorld');">Call to a function inside the namespace&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'helloWorld');">Call to a function inside the namespace</a></p>

<pre class="code"> 
CODE (Error "page not found"):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'loadErrorUrl');">Error "page not found"&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'loadErrorUrl');">Error "page not found"</a></p>    

<pre class="code"> 
CODE (Call to a global function - message):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.show_message('This is an test message!');">Call to a global function - message&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.show_message('This is an test message!');">Call to a global function - message</a></p>  

<pre class="code"> 
CODE (Call to a global function - errormessage):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.show_errormessage('This is an test error-message!');">Call to a global function - error&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.show_errormessage('This is an test error-message!');">Call to a global function - error</a></p>  

<pre class="code"> 
CODE (database-model test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'modelTest');">database-model test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'modelTest');">database-model test</a></p>
 
<pre class="code"> 
CODE (library test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'libraryTest');">library test&lt;/a>&lt;/p>        
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'libraryTest');">library test</a></p>        
   
<pre class="code"> 
CODE (parameter and post test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'parameterTest', &lt;?php echo $userID; ?&gt;, 'A multiparam test.');">parameter and post test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'parameterTest', <?php echo $userID; ?>, 'A multiparam test.');">parameter and post test</a></p>   

<pre class="code"> 
CODE (setPartialContent test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(&lt;?php echo $pwID; ?&gt;, 'partialTest');">setPartialContent test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $pwID; ?>, 'partialTest');">setPartialContent test</a></p>   
  
<pre class="code">
CODE (Image from widget-folder):
    &lt;p>&lt;img src="&lt;?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" />&lt;/p>
</pre>
    
<p><img src="<?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" /></p>
<h2>Debug message</h2>

<pre class="code">
CODE:
    &lt;p>&lt;a href="javascript:void(0);" onclick="log_message('Just a message');">Log a message (1)&lt;/a>&lt;/p>
</pre>
<p><a href="javascript:void(0);" onclick="log_message('Just a message');">Log a message (1)</a></p>

<pre class="code">
CODE:
    &lt;p>&lt;a href="javascript:void(0);" onclick="log_message('Anooother message');">Log a message (2)&lt;/a>&lt;/p>
</pre>
<p><a href="javascript:void(0);" onclick="log_message('Anooother message');">Log a message (2)</a></p>

<script type="text/javascript">
    function test_dump1() {
        var testArray = [];
        testArray[0] = 'value1';
        testArray[1] = 'something else';
        testArray[2] = true;
        
        log_variable(null, testArray);
    }
</script>

<pre class="code">
CODE:
    &lt;script type="text/javascript">
        function test_dump1() {
            var testArray = [];
            testArray[0] = 'value1';
            testArray[1] = 'something else';
            testArray[2] = true;
        
                log_variable(null, testArray);
            }
    &lt;/script>
    
    &lt;p>&lt;a href="javascript:void(0);" onclick="test_dump1();">Log an array&lt;/a>&lt;/p> 
</pre>
<p><a href="javascript:void(0);" onclick="test_dump1();">Log an array (3)</a></p>

<br /><br /><br />
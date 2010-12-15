
    <style type="text/css">
        pre.code {margin-top:20px; margin-left:10px; padding:5px; background-color:#d9d9d9; border:2px dashed #f0f0f0;}
    </style>

<div id="ajax_template_wrapper" style="padding:15px;">
    
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
CODE (Error "page not found"):
    &lt;p>&lt;a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/this_is_an_url_that_does_not_work');">error "page not found"&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/this_is_an_url_that_does_not_work');">Error "page not found"</a></p>    
    
<pre class="code"> 
CODE (Call to a function inside the namespace):
    &lt;p>&lt;a href="javascript:ajaxTemplateWidget.example_showMessage('Hello World');">Call to a function inside the namespace&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:ajaxTemplateWidget.example_showMessage('Hello World');">Call to a function inside the namespace</a></p>

<pre class="code"> 
CODE (Call to a global function - error):
    &lt;p>&lt;a href="javascript:show_errormessage('This is a error message');">Call to a global function - error&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:Desktop.show_errormessage('This is a error message');">Call to a global function - error</a></p>  

<pre class="code"> 
CODE (database-model test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/show_documentation');">database-model test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/model_test');">database-model test</a></p>
 
<pre class="code"> 
CODE (library test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/library_test');">library test&lt;/a>&lt;/p>        
</pre>
    <p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/library_test');">library test</a></p>        
   
<pre class="code"> 
CODE (parameter and post test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/edit_user/&lt;?php echo $userID; ?>');">parameter and post test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/edit_user/<?php echo $userID; ?>');">parameter and post test</a></p>   

<pre class="code"> 
CODE (setPartialContent test):
    &lt;p>&lt;a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/partial');">setPartialContent test&lt;/a>&lt;/p>
</pre>
    <p><a href="javascript:void(0);" onclick="ajaxTemplateWidget.loadURL('/some_controller_name/partial');">setPartialContent test</a></p>   
    
    
<pre class="code">
CODE (Image from widget-folder):
    &lt;p>&lt;img src="&lt;?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" />&lt;/p>
</pre>
    
    <p><img src="<?php echo $widget_base_url; ?>images/Why_NORAD_Tracks_Santa.jpg" /></p>


</div>
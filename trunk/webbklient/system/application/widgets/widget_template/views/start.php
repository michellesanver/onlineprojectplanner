<html>
<head></head>

<body>

<h1>Widget template</h1>

<p>This view is loaded from the subfolder 'views' inside the folder for the widget.</p>

<p><a href="<?php echo $widget_url; ?>main/show_documentation">show jquery.window documentation</a></p>

<h2>Data sent to view</h2>

<p><strong>base_url:</strong><br /><?php echo $base_url; ?></p>
<p><strong>widget_url:</strong><br /><?php echo $widget_url; ?></p>


<h2>Examples</h2>
<p><a href="javascript:parent.templateWidget.example_setContent();" onclick="">Call to a function inside the namespace for widget</a></p>


</body>
</html>

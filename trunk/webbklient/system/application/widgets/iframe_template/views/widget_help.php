<html>
<head>
    <style type="text/css">
        h2 { margin:0; padding: 0; position: relative; top: 10px; }
        a.small { font-size: 85%; }
    </style>
</head>

<body>

    <h1>Iframe template; Widget help</h1>
    
    <p><strong>Contents:</strong>
        <ul>
            <li><a href="#folders">Folders</a></li>
            <li><a href="#common_javascript">Common Javascript</a></li>
            <li><a href="#urls">URL's</a></li>
            <li><a href="#settings_xml">Settings.xml</a></li>
            <li><a href="#setup_javascript">Setup (javascript</a></li>
        </ul>
    </p>
    
    <p><br/><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p> 
    
    <a name="folders"></a>
    <h2>Folders</h2>
    <p>Each widget is placed in <strong><em>system/application/widgets/[name]</em></strong> where <strong><em>[name]</em></strong> is the name
    of the widget without the brackets []. Inside the folder for the widget the structure is just like Codeigniter:</p>
    
    <p>
        <strong><em>[name]/controllers</em></strong>; controller-classes (inherit from CI Controller)<br/>
        <strong><em>[name]/js</em></strong>; external javascript (recommended, not an requirement)<br/>
        <strong><em>[name]/css</em></strong>; external stylesheets (recommended, not an requirement)<br/>
        <strong><em>[name]/libraries</em></strong>; libraries<br/>
        <strong><em>[name]/models</em></strong>; models (database, inherit from CI model)<br/>
        <strong><em>[name]/views</em></strong>; views (Iframe requires full html-pages for each view)
    </p>
    
    <p><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p>
    
    <a name="common_javascript"></a>
    <h2>Common Javascript</h2>
    
    <p>Online Project Planner provides a number of common global functions and variables that can be used:</p>
    
    <p><strong>Variables:</strong><br/>
        <strong><em>BASE_URL</em></strong>; base_url from CI (no index.php)<br/>
        <strong><em>SITE_URL</em></strong>; site_url from CI (WITH index.php)<br/>
        <strong><em>CURRENT_PROJECT_ID</em></strong>; ID to current project
    </p>
    
    <p><strong>Functions:</strong><br/>
        <strong><em>show_message(message)</em></strong>; this function will display an ok-message, parameter 'message' is a string<br/>
        <strong><em>show_errormessage(message)</em></strong>; this function will display an error-message, parameter 'message' is a string
    </p>
    
    <p><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p>
    
    <a name="urls"></a>
    <h2>URL's</h2>
    
    <p>Codeigniter has an extension in the library Router to make it possible to have another folder-structure and URL's. When calling for 
    a controller for a widget, use this syntax:</p>
    
    <p><em><strong>SITE_URL + "/widget/[name]/[controller]"</strong></em>; where <strong><em>[name]</em></strong> is the name
    of the widget and <strong><em>[controller]</em></strong> is the name of the controller (see folders.)</p>
    
    <p><strong>Example:</strong><br/>
    <strong><em>SITE_URL + "/widget/iframe_template/main"</em></strong>; this will call the controller main for the widget iframe_template.
    </p>
    
    <p><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p>
    
    <a name="settings_xml"></a>
    <h2>Settings.xml</h2>
    
    <p>Each widget is required to have the file settings.xml inside the root-folder of the widget. Here is an example with comments:</p>
    
<pre>
    &lt;?xml version="1.0"?>
    &lt;!-- root -->
    &lt;settings>
        &lt;!--  short description of the widget -->
        &lt;about>A template and help for developing widgets with Iframe-style&lt;/about>
        &lt;!-- version of widget-->
        &lt;version>1.0&lt;/version>
        &lt;!-- link to website -->
        &lt;link>none&lt;/link>
        &lt;!-- name of author -->
        &lt;author>Fredrik Johansson&lt;/author>
        &lt;!-- icon to widget relative to main widget folder (can be left empty and a generic icon will be loaded) -->
        &lt;icon>help-desk-icon.png&lt;/icon>
        &lt;!-- title of widget; will be placed under icon (can be left empty) -->
        &lt;icon_title>Iframe Template&lt;/icon_title>
        &lt;!-- namespace of widget (namespace = javascript object) -->
        &lt;widget_object>iframeTemplateWidget&lt;/widget_object>
        &lt;!--
            list of files to be loaded relative to main folder of widgets
            (theese files will be loaded upon project start)
            
            valid types are; javascript, css
        -->
        &lt;load>
            &lt;file type="javascript">js/iframe-template.js&lt;/file>
            &lt;file type="css">css/iframe-template.css&lt;/file>
        &lt;/load>
    &lt;/settings>
</pre>
    
    <p><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p>
    
    <a name="setup_javascript"></a>
    <h2>Setup (javascript)</h2>
   
    <p>Set the name for the namespace of the widget in settings.xml and then create a javascript-file with the initial code to start a widget. Here
    is an example with comments:</p>
   
    <p><strong>Example:</strong></p>
   
<pre>
    // place widget in a namespace (javascript object simulates a namespace)
    iframeTemplateWidget = {

        // variable for window (DO NOT CHANGE - REQUIRED)
        wnd: null, 
        
        // callbacks that is set in common.js upon start (DO NOT CHANGE - REQUIRED)     
        onMinimize: null, 
        onClose:null,
        
        // function that will be called upon start (REQUIRED - do NOT change the name)
        open: function() {
            
                        // create a new jquery window
                        this.wnd = $('#content').window({
                            // change theese as needed
                           title: "Iframe Template 1.0",
                           url: SITE_URL+"/widget/iframe_template/main",
                           width: 680,
                           height: 400,
                           x: 30,
                           y: 15,
                           
                           // do NOT change theese
                           onMinimize:  this.onMinimize, 
                           onClose:  this.onClose,
                           checkBoundary: true,
                           maxWidth: $('#content').width(),
                           maxHeight: $('#content').height(),
                           bookmarkable: false
                        });
                        
                    }
        }
        
    };
</pre>
   
   <p><strong><em>Note 1:</em></strong> the jquery extended function $.window is a plugin that is loaded globally.<br/>
   <strong><em>Note 2:</em></strong> make sure that the function open is present in the namespace because that is the function
   that called when a user clicks on the icon.<br/>
   <strong><em>Note 3:</em></strong> also make sure that the namespace has the following variables: <em>wnd</em>, <em>onMinimize</em> and <em>onClose</em>.<br/>
   <strong><em>Note 4:</em></strong> and last.. make sure not to change the parameters after the comment "// do NOT change theese"
   </p>
   
   <p><a href="javascript:window.back(-1);" class="small"><< Back to previous page</a></p> 
    
   <p><br/></p>
   <p><br/></p>
    
</body>
</html>

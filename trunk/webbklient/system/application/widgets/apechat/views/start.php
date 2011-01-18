    
    <div id="ape_master_container"></div>
    
    <script type="text/javascript">
       
        // Add Session.js to APE JSF to handle multitab and page refresh
        APE.Config.scripts.push(APE.Config.baseUrl+'/Source/Core/Session.js');

        // Initialize APE_Client
        var chat = new APE.Chat({'container':MooTools('ape_master_container')});

        chat.addEvent('load', function() {
            //chat.core.start({"name":"jan"});
            chat.core.start({"name": prompt('Your name?')});
        });
        
        chat.addEvent('ready', function() {
            console.log('Your client is now connected');
        });
        
        // Connect to the APE Server
        chat.load({
            identifier: 'chatdemo', // Identifier of the application 
            channel: 'grupprum' // Channel to join at startup
        });            
        
    </script>
 /***
 * APE JSF Setup
 */
APE.Config.baseUrl = 'http://pppp.nu/ape-jsf'; //APE JSF 
APE.Config.domain = 'pppp.nu'; 
APE.Config.server = 'pppp.nu:6969'; //APE server URL 

/*APE.Config.baseUrl = 'http://ape-test.local/ape-jsf';
APE.Config.domain = 'ape-test.local';
APE.Config.server = 'ape-test.local:6969'; */

(function(){
	for (var i = 0; i < arguments.length; i++)
		APE.Config.scripts.push(APE.Config.baseUrl + '/Source/' + arguments[i] + '.js');
})('mootools-core', 'Core/APE', 'Core/Events', 'Core/Core', 'Pipe/Pipe', 'Pipe/PipeProxy', 'Pipe/PipeMulti', 'Pipe/PipeSingle', 'Request/Request','Request/Request.Stack', 'Request/Request.CycledStack', 'Transport/Transport.longPolling','Transport/Transport.SSE', 'Transport/Transport.XHRStreaming', 'Transport/Transport.JSONP', 'Core/Utility', 'Core/JSON');

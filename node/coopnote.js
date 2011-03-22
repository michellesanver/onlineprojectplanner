/**
 * @author Dennis Sangmo
 */
var http = require('http'),
	sys  = require('sys'),
	fs   = require('fs'),
	url  = require('url'), 
	io   = require('socket.io');
	
var clients = [];

var server = http.createServer(function(request, response){
	response.writeHead(200, {
		'Content-Type': 'text/html'
	});
	response.write('request.session: \n' + JSON.stringify(request.session, 2, true));
	response.end();
});

var socket = io.listen(server);

socket.on('connection', function(client){
	
	client.on('message', function(message){
		var jsonMess = eval("("+message+")");
		
		switch(jsonMess.type){
			case "firstconnect":
				client.projectId = jsonMess.projectId;
				clients.push(client);
				console.log("A client has connected from project " + client.projectId);
				break;
			case "subscribe":
				client.channel = jsonMess.channel;
				console.log("A client is now subscribing to channel " + client.channel);
				break;
			case "reloadList":
				sendMessageToProject(message, jsonMess.projectId, client);
				break;
			default:
				sendMessageToChannel(message, jsonMess.channel, client);
		}
	});
	
	client.on('disconnect', function(){
		for(var i in clients){
			if (clients[i] === client) {
				clients.splice(i, 1);
				break;
			}
		}
		console.log("A client has disconnected!");
	});
});

server.listen(4001);


//================
// Functions
//================
function sendMessageToChannel(message, channel, client) {
	for(var i in clients){
		if (clients[i] !== client) {
			if (clients[i].channel == channel) {
				clients[i].send(message);
			}
		}
	}
	console.log("A message has been sent to channel " + channel);
}

function sendMessageToProject(message, projectId, client) {
	for(var i in clients){
		if (clients[i] !== client) {
			if (clients[i].projectId == projectId) {
				clients[i].send(message);
			}
		}
	}
	console.log("A message has been sent to project " + projectId);
}
		

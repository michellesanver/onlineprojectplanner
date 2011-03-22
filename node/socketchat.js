/**
 * @author martinlindberg
 */
var http = require('http'),
	sys  = require('sys'),
	fs   = require('fs'),
	url  = require('url'), 
	io   = require('socket.io'),
	session = require('./core').magicSession();
	
var clients = [];
var colors = [];
var user = "";
var channel = "";

var server = http.createServer(function(request, response){
	response.writeHead(200, {
		'Content-Type': 'text/html'
	});
	//response.write('request.session: \n' + JSON.stringify(request.session, 2, true));
	request.session;
	
	
	var temp = url.parse(request.url, true).query || {};
	channel = temp.channel;
	user = temp.name;
	
	var rs = fs.createReadStream(__dirname + '/template.html');
	sys.pump(rs, response);
	
	
});

var socket = io.listen(server);

socket.on('connection', function(client){
	
		var username = user;
		var color = "color" + (Math.floor(Math.random()*10)+1);
		colors.push(color);
		var nr = clients.push(username);
		
		client.on('message', function(message){
			//sätter usrname första gången 
			/*if(!username){
				username = message;
				client.send('welcome: ' + username);
				return;
			}*/
		
			//byta namn genom att skriva /usr namn
			var mess = message.toString();
			if((mess.indexOf("/user ")) >-1)
			{
				var posUsr = mess.indexOf("/usr ");
				username = mess.slice(posUsr+6);
				console.log(mess.slice(posUsr+6));
				return;
			}
			if((mess.indexOf("/channel ")) >-1)
			{
				var posUsr = mess.indexOf("/channel ");
				channel = mess.slice(posUsr+9);
				console.log(mess.slice(posUsr+9));
				return;
			}
		
			//skriva ut tid ifrån server
		 	var currentTime = new Date();
		 	var hours = currentTime.getHours();
	 		var minutes = currentTime.getMinutes();
		 	if (minutes < 10)
				minutes = "0" + minutes;
			var tt = (' ' + hours + ":" + minutes);
		
			socket.broadcast('<div class="node_top '+ color +'">' + username + ' says: <span class="node_right">' + tt + '</span></div><div class="node_mess"> '+ message + '</div>');
		});
		
		 client.on('disconnect', function(){
			console.log(username + " nerkopplad");
			clients.pop(nr);
			colors.pop(nr);
			
			var alla = '/name';
			for(i=0;i<clients.length;i++)
			{
				alla += '<li class="'+ colors[i] +' ur">'  + clients[i] +'</li>';
			}	
			socket.broadcast(alla);
		}); 
	var alla = '/name';
	for(i=0;i<clients.length;i++)
	{
		alla += '<li class="'+ colors[i] +' ur">'  + clients[i] +'</li>';
	}	
	socket.broadcast(alla);
});


server.listen(4000);


		

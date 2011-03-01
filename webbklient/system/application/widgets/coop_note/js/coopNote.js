/* 
* Name: coopNote
* Desc: A cooperative notepad
* Last update: 28/2-2011 by Dennis Sangmo
*/
function coopNote(id, wnd_options) {
	this.widgetName = "coop_note";
	this.title = "Coop Note";
	var partialClasses = ['pad-list-area', 'pad-area'];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = false;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	//Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
}

coopNote.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
coopNote.prototype.index = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/notepad_contr/index/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "initEvents");
}

coopNote.prototype.initEvents = function(data){
	this.setWindowContent(data);
	
	this.startListEvents();
	this.startPadEvents();
	
	this.onNodeConnection();
}

coopNote.prototype.reloadBoth = function(padId){
	this.reloadList();
	this.reloadPad(padId);
}

coopNote.prototype.reloadList = function(noSend){
	/*if(noSend == undefined){
		this.socket.send("/rlw "+this.padId);
	}*/
	var url = SITE_URL+'/widget/' + this.widgetName + '/notepad_contr/reloadList/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "initListEvents", true);
}

coopNote.prototype.reloadPad = function(padId){
	if(padId != "new"){
		this.padId = padId;
		this.subChannel(this.padId);
	}
	
	var url = SITE_URL + '/widget/' + this.widgetName + '/notepad_contr/select/' + Desktop.currentProjectId + '/' + padId;
	ajaxRequests.load(this.id, url, "initPadEvents", true);
}

coopNote.prototype.initListEvents = function(data){
	this.setWindowContent([data, 'pad-list-area']);
	
	this.startListEvents();
}

coopNote.prototype.initPadEvents = function(data){
	this.setWindowContent([data, 'pad-area']);
	
	this.startPadEvents();
}

//=======================
// Event initfunctions
//=======================

coopNote.prototype.startListEvents = function(){
	var that = this;
	
	$('#' + this.divId + ' #pad-list-area').find('a').click(function(e){
		that.padId = $(this).attr("padId");
		
		that.reloadPad(that.padId);
		
		return false;
	});
	
	$('#' + this.divId + ' #pad-list-area').find('.delete').click(function(e){
		var delpadId = $(this).attr("padId");
		/*$('#' + that.divId).find( "#notepad-dialog-delete" ).dialog({
			resizable: false,
			height: 185 ,
			width: 400,
			modal: true,
			zIndex: 3999,
			buttons: {
				"Continue": function() {
					$( this ).dialog( "close" );
					var url = SITE_URL + '/widget/' + that.widgetName + '/notepad_contr/delete/' + delpadId;
					ajaxRequests.load(that.id, url, "catchStatus", true);
					
					if(that.padId == delpadId){
						that.reloadPad("new");
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});*/
		
		var url = SITE_URL + '/widget/' + that.widgetName + '/notepad_contr/delete/' + delpadId;
		ajaxRequests.load(that.id, url, "catchStatus", true);
		
		if(that.padId == delpadId){
			that.reloadPad("new");
		}
		
		return false;
	});
}

coopNote.prototype.startPadEvents = function(){
	var that = this;
	var newInput = $('#' + this.divId + ' #pad-area').find('#new_name');
	if(newInput.length == 1){
		newInput.focus(function(e){
			newInput.val("");
			newInput.unbind('focus');
		});
		
		//clickevent on savebutton when new pad
		$('#' + this.divId + ' #pad-area').find('#save_btn').click(function(e){
			var data = new Array();
			
			var name = [];
			name['name'] = 'Name';
			name['value'] = newInput.val();
			data.push(name);
			
			var text = [];
			text['name'] = 'Text';
			text['value'] = $('#' + that.divId + ' #pad').val();
			data.push(text);
			
			var url = SITE_URL + '/widget/' + that.widgetName + '/notepad_contr/save/' + Desktop.currentProjectId;
			ajaxRequests.post(that.id, data, url, "catchStatus", true);
			
			return false;
		});
	} else {
		// clickevent on savebutton when update
		$('#' + this.divId + ' #pad-area').find('#save_btn').click(function(e){
			var padId = $('#' + that.divId + ' #pad').attr("padId");
			
			// Creating the post array
			var data = new Array();
			
			var text = [];
			text['name'] = 'Text';
			text['value'] = $('#' + that.divId + ' #pad').val();
			data.push(text);
			
			var url = SITE_URL+'/widget/' + that.widgetName + '/notepad_contr/update/' + padId;
			ajaxRequests.post(that.id, data, url, "catchStatus", true);
			
			return false;
		});
		
		// Sends data to node
		$("#" + this.divId).find("#pad").keyup(function(e){
			var str = $("#" + that.divId).find("#pad").val();
			that.socket.send("/c "+that.padId+"/t " + str);
		});
		
	}
}

//=======================
// Node functions
//=======================

coopNote.prototype.onNodeConnection = function(){
	var that = this;
	
	this.socket = new io.Socket('pppp.nu', {port: 4001});
	this.socket.connect();
	
	this.socket.on('connect', function(){
	}); 
	
	this.socket.on('message', function(message){
		/*var mess = message.toString();
		if ((mess.indexOf("/rlw ")) > -1) {
			that.reloadList(true);
			return;
		}*/
		$("#" + that.divId).find("#pad").val(message);
	});
}

coopNote.prototype.subChannel = function(padId){
	if (this.socket == undefined) {
		this.onNodeConnection();
	}
	this.socket.send("/sc "+padId)
}
/*
// Eventcatcher. Catches the new settingsdata
ajaxTemplateWidget.prototype.settingsEventTest = function(data) {
	alert($.dump(data));
}
*/
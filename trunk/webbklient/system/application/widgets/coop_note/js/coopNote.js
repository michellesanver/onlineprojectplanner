/* 
* Name: coopNote
* Desc: A cooperative notepad
* Last update: 7/3-2011 by Dennis Sangmo
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

coopNote.prototype.reloadList = function(){
	var url = SITE_URL+'/widget/' + this.widgetName + '/notepad_contr/reloadList/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "initListEvents", true);
}

coopNote.prototype.reloadPad = function(padId){
	if(padId != "new"){
		this.subChannel(padId);
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
		that.reloadPad($(this).attr("padId"));
		
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
		ajaxRequests.load(that.id, url, "catchCoopnoteStatus", true);
		
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
			ajaxRequests.post(that.id, data, url, "catchCoopnoteStatus", true);
			
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
			var mess = "";
			var div = $("#" + that.divId).find("#pad");
			var selection = $(div).getSelection();
			var areaValue = div.val();
			
			// test to see if keypress is a char. (space, a-ö, 0-9, special chars)
			if (e.which == 32 || (e.which >= 48 && e.which <= 90) || e.which == 221 || e.which == 222 || e.which == 192 || (e.which >= 96 && e.which <= 111) || (e.which >= 186 && e.which <= 222)) {
				var char = areaValue.toString().slice(selection.start-1, selection.start);
				
				mess = "{type:\"char\", pos:" + (selection.start-1) + ", char:\"" + char + "\", channel:" + that.padId + "}";
			} else if (e.which == 13) {
				mess = "{type:\"enter\", pos:" + (selection.start-1) + ", channel:" + that.padId + "}";
			} else if (e.which == 8) {
				mess = "{type:\"backspace\", pos:" + (selection.start + 1) + ", channel:" + that.padId + "}";
			} else if (e.which == 46) {
				mess = "{type:\"delete\", pos:" + (selection.start) + ", channel:" + that.padId + "}";
			}
			
			if(mess !== "")
				that.socket.send(mess);
		});
		
	}
}

//altered status catcher to reload documentlist
coopNote.prototype.catchCoopnoteStatus = function(data) {
	var json;
	if(json = $.parseJSON(data)){
		// Everything went ok
		if(json.status == "ok") {
			Desktop.show_message(json.status_message);
			this.socket.send("{type:\"reloadList\", projectId:"+Desktop.currentProjectId+"}");
			// Calling the requested function
			if(json.load != undefined) {
				this[json.load](json.loadparams);
			}
		} else {
			Desktop.show_errormessage(json.status_message);
		}
	} else {
		Desktop.show_errormessage("A error has occurred, admins has been informed!");
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
		that.socket.send("{type:\"firstconnect\", projectId:"+Desktop.currentProjectId+"}");
	}); 
	
	this.socket.on('message', function(message){
		var div = $("#" + that.divId).find("#pad");
		var jsonMess = eval("("+message+")");
		var selection = $(div).getSelection();
		var val = div.val();
		
		switch(jsonMess.type){
			case "char":
				var newVal = val.substring(0, jsonMess.pos) + jsonMess.char + val.substring(jsonMess.pos);
				if (selection.start >= jsonMess.pos) {
					selection.start += jsonMess.char.length;
					selection.end += jsonMess.char.length;
				}
				div.val(newVal);
				if (selection != undefined) {
					$(div).setSelection(selection.start, selection.end);
				}
				break;
			case "reloadList":
				that.reloadList();
				break;
			case "enter":
				var newVal = val.substring(0, jsonMess.pos) + "\n" + val.substring(jsonMess.pos);
				if (selection.start >= jsonMess.pos) {
					selection.start += 1;
					selection.end += 1;
				}
				div.val(newVal);
				if (selection != undefined) {
					$(div).setSelection(selection.start, selection.end);
				}
				break;
			case "backspace":
				var newVal = val.substring(0, jsonMess.pos - 1) + val.substring(jsonMess.pos);
				if (selection.start >= jsonMess.pos) {
					selection.start -= 1;
					selection.end -= 1;
				}
				div.val(newVal);
				if (selection != undefined) {
					$(div).setSelection(selection.start, selection.end);
				}
				break;
			case "delete":
				var newVal = val.substring(0, jsonMess.pos) + val.substring(jsonMess.pos+1);
				if (selection.start > jsonMess.pos) {
					selection.start -= 1;
					selection.end -= 1;
				}
				div.val(newVal);
				if (selection != undefined) {
					$(div).setSelection(selection.start, selection.end);
				}
				break;
		}
	});
}

coopNote.prototype.subChannel = function(padId){
	if (this.socket == undefined) {
		this.onNodeConnection();
	}
	this.padId = padId;
	this.socket.send("{type:\"subscribe\", channel:"+padId+"}")
}
/* 
* Name: Project settings
* Desc: Static object for the Project Settings widget
* Last update: 16/2-2011 Dennis Sangmo
*/
function projectsettings(id, divid, widgeturl) {
	this.widgetName = "project_settings";
	this.title = "Project Settings";
	this.id = id;
	this.divId = divid;
	this.widgetUrl = widgeturl;
}

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
projectsettings.prototype.index = function() {
	ajaxRequests.load(this.id, this.widgetUrl + '/ps_controller/index/' + this.id + '/' + Desktop.currentProjectId, "PS_eventinit", true);
}

projectsettings.prototype.eventinit = function(){
	var that = this;
	
	// forumevent
	$("#"+ this.divId).find('#proj_desc_' + Desktop.currentProjectId).submit(function(){
		that.save();
		return false;
	});
	
	// deleteevent
	$("#"+ this.divId).find('#delete_btn').click(function(){
		$('#' + that.divId).find( "#project-settings-dialog-delete" ).dialog({
			resizable: false,
			height: 185 ,
			width: 400,
			modal: true,
			zIndex: 3999,
			buttons: {
				"Continue": function() {
					$( this ).dialog( "close" );
					ajaxRequests.load(that.id, that.widgetUrl + '/ps_controller/delete/'+ Desktop.currentProjectId, "PS_blockUser", true);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
		return false;
	});
}

// Submit btn on form
projectsettings.prototype.save = function(){
	if($("#"+ this.divId).find('#proj_desc_' + Desktop.currentProjectId).valid()) {
		
		var desc = $("#"+ this.divId).find('#proj_desc_' + Desktop.currentProjectId + " #Description").attr('value');
		
		var formArray = new Array()
		var tmp1 = [];
		tmp1['name'] = 'Project_id';
		tmp1['value'] = Desktop.currentProjectId;
		formArray.push(tmp1);
		var tmp2 = [];
		tmp2['name'] = 'Description';
		tmp2['value'] = desc;
		formArray.push(tmp2);
		
		ajaxRequests.post(this.id, formArray, this.widgetUrl + 'ps_controller/saveDescription/', "catchStatus", true);
	}
	
	return false;
}

// Blocks user from further interactions
projectsettings.prototype.blockUser = function(data){
	var json;
	if(json = $.parseJSON(data)){
		// Everything went ok
		if(json.status == "ok") {
			$('#fullpage_overlay').show();
			$('#fullpage_overlay').click(function(){});
			$('#message').html('<p>The project has been deleted!</p>'+'<p><a href="'+SITE_URL+'">Click here to continue!</a></p>');
			$('#message').css('top', '0px');
			$('#message').css('display', 'block');
			$('#message').click(function(){});
			var maxWidth = $('#desktop').width();
			var centerPosition = (maxWidth/2)-(Desktop.message_width/2);
			$('#message').css('left', centerPosition+'px');
			$('#message').addClass('ok');
		} else {
			Desktop.show_errormessage(json.status_message);
		}
	} else {
		Desktop.show_errormessage("A error has occurred, admins has been informed!");
	}
}

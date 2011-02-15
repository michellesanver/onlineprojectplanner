/*
* Author: Dennis Sangmo
* Description: 
* Static object for the Project Settings widget
* 
*/

function projectsettings(id, wnd_options) {
	this.widgetName = "project_settings";
	var partialClasses = [];
	
	// set options for window
	wnd_options.title = "Project Settings";
	wnd_options.allowSettings = false;
	
	this.create(id, wnd_options, partialClasses);
}

projectsettings.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
projectsettings.prototype.index = function() {
	// load the first page upon start
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/ps_controller/index/' + this.id + '/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "setWindowContent");
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
		
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/ps_controller/saveDescription/';
		ajaxRequests.post(this.id, formArray, url, "catchStatus");
	}
	
	return false;
}

// Delete project
projectsettings.prototype.del = function(){
	if(confirm("Are you sure you want to delete this project?")) {
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/ps_controller/delete/'+ Desktop.currentProjectId;
		ajaxRequests.load(this.id, url, "blockUser");
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
			$('#message').html('<p>The project has been deleted!</p>'+'<p><a href="'+SITE_URL+'">Click here to continue!</a></p>');
			$('#message').css('top', '0px');
			$('#message').css('display', 'block');
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

// place widget in a namespace (javascript object simulates a namespace)
function projectmembers(id, wnd_options) {
	this.widgetName = "project_members";
	var partialClasses = [];
	
	// set options for window
	wnd_options.title = "Project Members";
	wnd_options.allowSettings = false;
	
	this.create(id, wnd_options, partialClasses);
}

projectmembers.Inherits(Widget);

projectmembers.prototype.setContent = function(args) {
	this.setWindowContent(args);
	
	// Enable the JS validation
	$('#proj_mem_' + Desktop.currentProjectId).validate();
}

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
projectmembers.prototype.index = function() {
	// load the first page upon start
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/index/' + Desktop.currentProjectId + '/' + this.id;
	ajaxRequests.load(this.id, url, "setContent");
}

projectmembers.prototype.save = function() {
	if($('#proj_mem_' + Desktop.currentProjectId).valid()) {
		
		var email = $('#proj_mem_' + Desktop.currentProjectId + " #email").attr('value');
		var role = $('#proj_mem_' + Desktop.currentProjectId + " #projectRoleID").attr('value');
		
		var formArray = new Array()
		var tmp1 = [];
		tmp1['name'] = 'projectID';
		tmp1['value'] = Desktop.currentProjectId;
		formArray.push(tmp1);
		var tmp2 = [];
		tmp2['name'] = 'email';
		tmp2['value'] = email;
		formArray.push(tmp2);
		var tmp3 = [];
		tmp3['name'] = 'projectRoleID';
		tmp3['value'] = role;
		formArray.push(tmp3);
		
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/save/';
		ajaxRequests.post(this.id, formArray, url, "catchStatus", true);
	}
	return false;
}

projectmembers.prototype.switchgeneral = function(victim) {
	if(confirm("Are you sure you want to promote this member to general?")) {
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/switchGeneral/'+ victim + '/' + Desktop.currentProjectId;
		ajaxRequests.load(this.id, url, "catchStatus", true);
	}
}

projectmembers.prototype.kick = function(victim) {
	if(confirm("Are you sure you want to kick this member?")) {
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/kickOut/'+ victim + '/' + Desktop.currentProjectId;
		ajaxRequests.load(this.id, url, "catchStatus", true);
	}
}

projectmembers.prototype.leave = function() {
	/*$( "#"+this.dialogId ).dialog({
		resizable: false,
		height: 175 ,
		width: 400,
		modal: true,
		zIndex: 3999,
		buttons: {
			"Continue": function() {
				$( this ).dialog( "close" );
				//var url = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/leave/' + Desktop.currentProjectId;
				//ajaxRequests.load(this.id, url, "projectmembers.setAjaxError", true);
				alert(this.id);
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});*/
	if(confirm("Are you sure you want to leave this project?")) {
		var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/leave/' + Desktop.currentProjectId;
		ajaxRequests.load(this.id, url, "catchStatus", true);
	}
}

projectmembers.prototype.promote = function(proj_mem_id) {
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/promoteToAdmin/' + proj_mem_id + '/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "catchStatus", true);
}

projectmembers.prototype.demote = function(proj_mem_id) {
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/pm_controller/demoteToMember/' + proj_mem_id + '/' + Desktop.currentProjectId;
	ajaxRequests.load(this.id, url, "catchStatus", true);
}
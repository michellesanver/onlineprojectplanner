/* 
* Name: Project member
* Desc: A widget to handle all the members of the project
* Last update: 16/2-2011 Dennis Sangmo
*/
function projectmembers(id, divid, widgeturl) {
	this.widgetName = "project_members";
	this.title = "Project Members";
	this.id = id;
	this.divId = divid;
	this.widgetUrl = widgeturl;
}

// Overwriting the index function!
projectmembers.prototype.index = function() {
	// load the first page upon start
	ajaxRequests.load(this.id, this.widgetUrl + 'pm_controller/index/' + Desktop.currentProjectId + '/' + this.id, "PM_eventinit", true);
}

projectmembers.prototype.eventinit = function() {
	var that = this;
	

	
	// Formevents
	$('#' + this.divId).find('#proj_mem_' + Desktop.currentProjectId).validate();
	$('#' + this.divId).find('#proj_mem_' + Desktop.currentProjectId).submit(function(){
		that.save();
		return false;
	});
	
	// Leaveevent
	$('#' + this.divId).find('#leave_btn').click(function(){
		$('#' + that.divId).find( "#project-member-dialog-leave" ).dialog({
			resizable: false,
			height: 185 ,
			width: 400,
			modal: true,
			zIndex: 3999,
			buttons: {
				"Continue": function() {
					$( this ).dialog( "close" );
				
					ajaxRequests.load(that.id, that.widgetUrl + 'pm_controller/leave/' + Desktop.currentProjectId, "catchStatus", true);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		return false;
	});
	
	// Promoteevent
	$('#' + this.divId).find('.promote_btn').click(function(){
	
		ajaxRequests.load(this.id, this.widgetUrl + '/pm_controller/promoteToAdmin/' + $(this).attr("pmID") + '/' + Desktop.currentProjectId, "catchStatus", true);
		return false;
	});
	
	// Demoteevent
	$('#' + this.divId).find('.demote_btn').click(function(){
	
		ajaxRequests.load(this.id, this.widgetUrl + 'pm_controller/demoteToMember/' + $(this).attr("pmID") + '/' + Desktop.currentProjectId, "catchStatus", true);
		return false;
	});
	
	// Kickevent
	$('#' + this.divId).find('.kick_btn').click(function(){
		var uid = $(this).attr("uID");
		$('#' + that.divId + " #project-member-dialog-kick" ).dialog({
			resizable: false,
			height: 185 ,
			width: 400,
			modal: true,
			zIndex: 3999,
			buttons: {
				"Continue": function() {
					$( this ).dialog( "close" );
					
					ajaxRequests.load(that.id, that.widgetUrl + 'pm_controller/kickOut/'+ uid + '/' + Desktop.currentProjectId, "catchStatus", true);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		return false;
	});
	
	// Switchgeneralevent
	$('#' + this.divId).find('.switchgeneral_btn').click(function(){
		var uid = $(this).attr("uID");
		$('#' + that.divId).find( "#project-member-dialog-switch" ).dialog({
			resizable: false,
			height: 185 ,
			width: 400,
			modal: true,
			zIndex: 3999,
			buttons: {
				"Continue": function() {
					$( this ).dialog( "close" );
			
					ajaxRequests.load(that.id, that.widgetUrl + 'pm_controller/switchGeneral/'+ uid + '/' + Desktop.currentProjectId, "catchStatus", true);
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		return false;
	});
	
}

/*
* Here comes all happening function. Executed from html links
* 
*/

projectmembers.prototype.save = function() {
	if($('#' + this.divId).find('#proj_mem_' + Desktop.currentProjectId).valid()) {

		var email = $('#' + this.divId).find('#proj_mem_' + Desktop.currentProjectId + " #email").attr('value');
		var role = $('#' + this.divId).find('#proj_mem_' + Desktop.currentProjectId + " #projectRoleID").attr('value');
		
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
		
		ajaxRequests.post(this.id, formArray, this.widgetUrl + 'pm_controller/save/', "catchStatus", true);
	}
	return false;
}

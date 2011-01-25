/*
* Author: Dennis Sangmo
* Description: 
* Static object for the Project Settings widget
* 
*/
projectsettings = {

    // widget specific settings
    partialContentDivClass: '', // optional
    widgetTitle: 'Project Settings',
    widgetName: 'project_settings', // also name of folder
	
	currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
		// set options for window
		var windowOptions = {
			// change theese as needed
			title: projectsettings.widgetTitle,
			width: 450,
			height: 500,
			x: 30,
			y: 15,
			allowSettings: false
		};
	  
		// create window
		Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, projectsettings.partialContentDivClass, last_position);
		
		projectsettings.index();
	},
	
	// Fist function to be executed
	index: function() {
		// load the first page upon start
		var loadFirstPage = SITE_URL+'/widget/' + projectsettings.widgetName + '/ps_controller/index/' + Desktop.currentProjectId;
		ajaxRequests.load(loadFirstPage, "projectsettings.setContent", "projectsettings.setAjaxError");
	},
	
	// This function executes when the form is submited
	saveDescription: function() {
		if($('#proj_desc_' + Desktop.currentProjectId).valid()) {
			
			var desc = $('#proj_desc_' + Desktop.currentProjectId + " #Description").attr('value');
			
			var formArray = new Array()
			var tmp1 = [];
			tmp1['name'] = 'Project_id';
			tmp1['value'] = Desktop.currentProjectId;
			formArray.push(tmp1);
			var tmp2 = [];
			tmp2['name'] = 'Description';
			tmp2['value'] = desc;
			formArray.push(tmp2);
			
			var url = SITE_URL+'/widget/' + projectsettings.widgetName + '/ps_controller/saveDescription/';
			ajaxRequests.post(formArray, url, "projectsettings.catchStatus", "projectsettings.setAjaxError", true);
		}
		return false;
	},
	
	//If the delete button was clicked this function is executed
	deleteProj: function() {
		if(confirm("Are you sure you want to delete this project?")) {
			var loadFirstPage = SITE_URL+'/widget/' + projectsettings.widgetName + '/ps_controller/delete/'+ Desktop.currentProjectId;
			ajaxRequests.load(loadFirstPage, "projectsettings.catchStatus", "projectsettings.setAjaxError", true);
			projectsettings.index();
		}
	},
	
	/* 
	* The following functions are common for att widgets.
    * --------------------------------------------------------------------------------------- 
    */
	catchRedirectStatus: function(data) {
		data = unescape(data);
		var data_obj = $.parseJSON(data);
		if(data_obj.status == "ok") {
			projectsettings.index();
		} else {
			Desktop.show_errormessage(data_obj.status_message);
		}
	},
	
	// Catches status requests.
	catchStatus: function(data){
		data = unescape(data);
		var data_obj;
		if(data_obj = $.parseJSON(data)) {
			if(data_obj.status == "ok") {
				// If the project is deleted then force the user to click this link
				if(data_obj.deleted == "yes") {
					$('#fullpage_overlay').show();
					$('#message').html('<p>The project has been deleted!</p>'+'<p><a href="'+SITE_URL+'">Click here to continue!</a></p>');
					$('#message').css('top', '0px');
					$('#message').css('display', 'block');
					var maxWidth = $('#desktop').width();
					var centerPosition = (maxWidth/2)-(Desktop.message_width/2);
					$('#message').css('left', centerPosition+'px');
					$('#message').addClass('ok');
				} else {
					Desktop.show_message(data_obj.status_message);
					if(data_obj.reload == "yes") {
						projectsettings.index();
					}
				}
			} else {
				Desktop.show_errormessage(data_obj.status_message);
			}
		} else {
			Desktop.show_errormessage("A error has occurred! Admins has been informed.");
		}
	},
		
    // set content in widgets div, called from the ajax request
    setContent: function(data) {
		data = unescape(data);
		// The success return function, the data must be unescaped befor use.
		// This is due to ILLEGAL chars in the string.
		Desktop.setWidgetContent(data);
		$('#proj_desc_' + Desktop.currentProjectId).validate();
    },
    
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL) {
		Desktop.show_ajax_error_in_widget(loadURL);
    }
};
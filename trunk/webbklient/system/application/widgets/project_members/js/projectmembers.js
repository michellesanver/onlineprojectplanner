// place widget in a namespace (javascript object simulates a namespace)
projectmembers = {

    // widget specific settings
    partialContentDivClass: '', // optional
    widgetTitle: 'Project Members',
    widgetName: 'project_members', // also name of folder
	
		currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: projectmembers.widgetTitle,
				width: 400,
				height: 450,
				x: 30,
				y: 15,
				allowSettings: false
			};
	      
			// create window
			Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, projectmembers.partialContentDivClass, last_position);
			
			projectmembers.index();
		},
		
		index: function() {
			// load the first page upon start
			var loadFirstPage = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/index/' + Desktop.currentProjectId;
			ajaxRequests.load(loadFirstPage, "projectmembers.setContent", "projectmembers.setAjaxError");
		},
		
		save: function() {
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
				
				var url = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/save/';
				ajaxRequests.post(formArray, url, "projectmembers.catchStatus", "projectmembers.setAjaxError", true);
			}
			return false;
		},
		
		kickout: function(victim) {
			if(confirm("Are you sure you want to kick this member?")) {
				var loadFirstPage = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/kickOut/'+ victim + '/' + Desktop.currentProjectId;
				ajaxRequests.load(loadFirstPage, "projectmembers.catchStatus", "projectmembers.setAjaxError", true);
				projectmembers.index();
			}
		},
		
		switchgeneral: function(victim) {
			if(confirm("Are you sure you want to promote this member to general?")) {
				var loadFirstPage = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/switchGeneral/'+ victim + '/' + Desktop.currentProjectId;
				ajaxRequests.load(loadFirstPage, "projectmembers.catchStatus", "projectmembers.setAjaxError", true);
				projectmembers.index();
			}
		},
		
		leave: function() {
			if(confirm("Are you sure you want to leave this project?")) {
				var loadFirstPage = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/leave/' + Desktop.currentProjectId;
				ajaxRequests.load(loadFirstPage, "projectmembers.catchRedirectStatus", "projectmembers.setAjaxError", true);
			}
		},
		
		promoteToAdmin: function(proj_mem_id) {
			var url = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/promoteToAdmin/' + proj_mem_id + '/' + Desktop.currentProjectId;
			ajaxRequests.load(url, "projectmembers.catchStatus", "projectmembers.setAjaxError", true);
		},
		
		demoteToMember: function(proj_mem_id) {
			var url = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/demoteToMember/' + proj_mem_id + '/' + Desktop.currentProjectId;
			ajaxRequests.load(url, "projectmembers.catchStatus", "projectmembers.setAjaxError", true);
		},
		
		
	/* 
	* The following functions are common for att widgets.
    * --------------------------------------------------------------------------------------- 
    */
		catchRedirectStatus: function(data) {
			data = unescape(data);
			var data_obj = $.parseJSON(data);
			if(data_obj.status == "ok") {
				projectmembers.index();
			} else {
				Desktop.show_errormessage(data_obj.status_message);
			}
		},
		
		catchStatus: function(data){
			data = unescape(data);
			var data_obj;
			if(data_obj = $.parseJSON(data)) {
				if(data_obj.status == "ok") {
					Desktop.show_message(data_obj.status_message);
					if(data_obj.reload == "yes") {
						projectmembers.index();
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
			$('#proj_mem_' + Desktop.currentProjectId).validate();
    },

    // set partial content in widgets div, called from the ajax request
    setPartialContent: function(data) {
			// The success return function, the data must be unescaped befor use.
			// This is due to ILLEGAL chars in the string.
			Desktop.setWidgetPartialContent(this.currentPartial, unescape(data));
			this.currentPartial = null;
    },
    
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL) {
			Desktop.show_ajax_error_in_widget(loadURL);
    }
};

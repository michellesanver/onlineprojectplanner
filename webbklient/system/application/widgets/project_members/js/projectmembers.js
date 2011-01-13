   
// place widget in a namespace (javascript object simulates a namespace)
projectmembers = {

    // widget specific settings
    partialContentDivClass: '', // optional
    widgetTitle: 'Project Members',
    widgetName: 'project_members', // also name of folder
	
		currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId) {
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: projectmembers.widgetTitle,
				width: 800,
				height: 450,
				x: 30,
				y: 15,
				allowSettings: false
			};
	      
			// create window
			Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, projectmembers.partialContentDivClass);
			
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
				
				var url = SITE_URL+'/widget/' + projectmembers.widgetName + '/pm_controller/index/' + Desktop.currentProjectId;
				ajaxRequests.post(formArray, url, "projectmembers.setContent", "projectmembers.setAjaxError", true);
			}
			return false;
		},
		
		
	/* 
	* The following functions are common for att widgets.
    * --------------------------------------------------------------------------------------- 
    */
		
    // set content in widgets div, called from the ajax request
    setContent: function(data) {
			data = unescape(data);
			if(data == "login_error") {
				Desktop.show_errormessage("You are not authenticated. Please login!");
			} else if(data == "member_error") {
				Desktop.show_errormessage("You are not a member of this project");
			} else {
				// The success return function, the data must be unescaped befor use.
				// This is due to ILLEGAL chars in the string.
				Desktop.setWidgetContent(data);
				$('#proj_mem_' + Desktop.currentProjectId).validate();
			}
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
    },
    
    // shows a message (example in start.php)
    example_showMessage: function(message) {
			Desktop.show_message(message);    
	},
    
    // wrapper-function that easily can be used inside views from serverside    
    loadURL: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+projectmembers.widgetName+url;
				
        // send request
        ajaxRequests.load(url, 'projectmembers.setContent', 'projectmembers.setAjaxError');
    },
		
		// Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
	loadURLtoPartialTest: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+projectmembers.widgetName+url;
				
        // set currentpartial to to the classname
        this.currentPartial = projectmembers.partialContentDivClass;
        
        // send request, last parameter = true if this is a partial call. Will skip the loading image.
        ajaxRequests.load(url, 'projectmembers.setPartialContent', 'projectmembers.setAjaxError', true);
    },
		
    // wrapper-function that easily can be used inside views from serverside
    postURL: function(formClass, url) {
        // prepare url
        url = SITE_URL+'/widget/'+projectmembers.widgetName+url;
				
		// catching the form data
		var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();
				
        // send request
        ajaxRequests.post(postdata, url, 'projectmembers.setContent', 'projectmembers.setAjaxError');   
    }
    
};

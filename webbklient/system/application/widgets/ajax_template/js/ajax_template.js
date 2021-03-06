/* 
* Name: AJAX Template
* Desc: A widget created only to be used as an example of how to create a widget.
* Last update: 3/2-2011 by Dennis Sangmo
*/
function ajaxTemplateWidget(id, wnd_options) {
	this.widgetName = "ajax_template";
	this.title = "AJAX template";
	var partialClasses = ['ajax_template_partial'];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = true;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
}

ajaxTemplateWidget.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
ajaxTemplateWidget.prototype.index = function() {
	// load the first page upon start
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/index/' + this.id;
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Simple function
ajaxTemplateWidget.prototype.helloWorld = function() {
	alert("You are now  in the widget " + this.title + " with the id " + this.id);
}

// Loads a non-existing page
ajaxTemplateWidget.prototype.loadErrorUrl = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/this_is_an_url_that_does_not_work';
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Loads a non-existing page
ajaxTemplateWidget.prototype.show_documentation = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/show_documentation/' + this.id;
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Tests to init content with a model
ajaxTemplateWidget.prototype.modelTest = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/model_test/' + this.id;
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Tests to init content with a library
ajaxTemplateWidget.prototype.libraryTest = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/library_test/' + this.id;
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Parameter test
ajaxTemplateWidget.prototype.parameterTest = function(args) {
	// If you send more than 1 parameter from your view, they will clog up to an array in the same order.
	var theString = args[1]; // the second parameter
	alert("Here's the string parameter: " + theString);
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/edit_user/' + this.id + '/' + args[0];
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Saves the form
ajaxTemplateWidget.prototype.saveUserForm = function() {
	// Gathering the data from the form with the selialize function.
	var postData = $('#' + this.divId).find('.form1').serialize();
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/save_edit_user/' + this.id;
	
	// The postcommand. No big change from the load command exept the postData-parameter.
	ajaxRequests.post(this.id, postData, url, "setWindowContent");
	
	// This function is used as an onSubmit event, thats why 'return false;' i very important.
	return false;
}

// Test html fetcher
ajaxTemplateWidget.prototype.partialTest = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/partial/' + this.id;
	ajaxRequests.load(this.id, url, "setWindowContent");
}

// Fetched the partial content to be inserted in the partial area
ajaxTemplateWidget.prototype.getPartialContent = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/some_controller_name/partialCall';
	
	// Send the partialname in the last parameter
	ajaxRequests.load(this.id, url, "setWindowContent", 'ajax_template_partial');
}

// Eventcatcher. Catches the new settingsdata
ajaxTemplateWidget.prototype.settingsEventTest = function(data) {
	alert($.dump(data));
}
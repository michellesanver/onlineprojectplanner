/* 
* Name: Organizer
* Desc: A collection of functions for projectadministration
* Last update: 15/2-2011 by Dennis Sangmo
*/
function organizer(id, wnd_options) {
	this.widgetName = "organizer";
	this.title = "Organizer";
	var partialClasses = ['project_member', 'project_settings', 'widget_handler'];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = false;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	//Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
}

organizer.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
organizer.prototype.index = function() {
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/organizer_contr/index/' + Desktop.currentProjectId + '/' + this.id;
	ajaxRequests.load(this.id, url, "indexSuccess");
}

organizer.prototype.indexSuccess = function(data){
	this.setWindowContent(data);
	
	$('#' + this.divId).find('.tabs a').click(function(e){
		Desktop.callWidgetFunction(this, "tabClick");
		return false;
	});
	
	this.activateTab("#tab1");
}

organizer.prototype.tabClick = function(clickedLink){
	this.activateTab('#'+clickedLink.id);
}

organizer.prototype.activateTab = function(name) {
	$('#' + this.divId).find('.tabs a').removeClass("active");
	$('#' + this.divId).find('.tabs '+name).addClass('active');
	
	$('#' + this.divId).find('#organizerContent div').css("display", "none");
	$('#' + this.divId).find('#organizerContent '+name).css("display", "block");
}

/*
// Eventcatcher. Catches the new settingsdata
ajaxTemplateWidget.prototype.settingsEventTest = function(data) {
	alert($.dump(data));
}
*/
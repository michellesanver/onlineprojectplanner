/* 
* Name: Organizer
* Desc: A collection of functions for projectadministration
* Last update: 16/2-2011 by Dennis Sangmo
*/
function organizer(id, wnd_options) {
	this.widgetName = "organizer";
	this.title = "Organizer";
	
	// Cache data
	this.WH_data = null;
	this.PM_data = null;
	this.PS_data = null;
	
	// Tab constnts
	this.WIDGETHANDLER = "tab1";
	this.MEMBERS = "tab2";
	this.SETTINGS = "tab3";
	
	var partialClasses = [this.WIDGETHANDLER+"_content", this.MEMBERS+"_content", this.SETTINGS+"_content"];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = false;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	//Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
	
	// Init objects
	var url = SITE_URL+'/widget/_core/' + this.widgetName + '/';
	this.widget_handler = new widgethandler(this.id, this.divId + ' #' + this.WIDGETHANDLER+"_content", url);
	this.project_members = new projectmembers(this.id, this.divId + ' #' + this.MEMBERS+"_content", url);
	this.project_settings = new projectsettings(this.id, this.divId + ' #' + this.SETTINGS+"_content", url);
}

organizer.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
organizer.prototype.index = function() {
	// set content
	this.setWindowContent(organizer_view.index());
	var that = this;
	
	/*
	 * Tab init
	 */
	$('#' + this.divId).find('.tabs a').click(function(e){
		that.activateTab(this.id);
		return false;
	});
	
	this.activateTab(this.WIDGETHANDLER);
	
}

organizer.prototype.activateTab = function(name) {
	$('#' + this.divId).find('.tabs a').removeClass("active");
	$('#' + this.divId).find('.tabs #'+name).addClass('active');
	
	$('#' + this.divId).find('#organizerContent .tab').css("display", "none");
	$('#' + this.divId).find('#organizerContent #'+name+'_content').css("display", "block");
	
	switch(name){
		case this.WIDGETHANDLER:
			if(this.WH_data === null)
				this.widget_handler.index()
			else
				this.WH_eventinit(this.WH_data);
			break;
		case this.MEMBERS:
			if(this.PM_data === null)
				this.project_members.index()
			else
				this.PM_eventinit(this.PM_data);
			break;
		case this.SETTINGS:
			if(this.PS_data === null)
				this.project_settings.index()
			else
				this.PS_eventinit(this.PS_data);
			break;
	}
}

/*
 * Widget_handler functions
 */
organizer.prototype.WH_eventinit = function(data){
	// cache
	this.WH_data = data;
	
	//set content
	this.setWindowContent(Array(data, this.WIDGETHANDLER+"_content"));
	this.widget_handler.eventinit();
}

/*
 * Project_member functions
 */
organizer.prototype.PM_index = function() {
	this.project_members.index();
}
organizer.prototype.PM_eventinit = function(data){
	// cache
	this.PM_data = data;
	
	//set content
	this.setWindowContent(Array(data, this.MEMBERS+"_content"));
	this.project_members.eventinit();
}

/*
 * Project_settings functions
 */
organizer.prototype.PS_index = function() {
	this.project_settings.index();
}
organizer.prototype.PS_eventinit = function(data){
	// cache
	this.PS_data = data;
	
	//set content
	this.setWindowContent(Array(data, this.SETTINGS+"_content"));
	this.project_settings.eventinit();
}
organizer.prototype.PS_blockUser = function(data){
	this.project_settings.blockUser(data);
}
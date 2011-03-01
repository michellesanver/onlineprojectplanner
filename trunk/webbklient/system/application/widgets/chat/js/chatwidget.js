/* 
* Name: 
* Desc: 
* Last update: 
*/
function chatWidget(id, wnd_options) {
	this.widgetName = "chat";
	this.title = "chat";
	var partialClasses = [''];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = true;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	//Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
}

chatWidget.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
chatWidget.prototype.index = function() {
	var url = SITE_URL+'/widget/' + this.widgetName + '/chat/index/' + this.id;
    ajaxRequests.load(this.id, url, "setWindowContent");
}


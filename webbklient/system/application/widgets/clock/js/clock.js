
function clockWidget(id, wnd_options) {

    this.widgetName = "clock";
    this.title = "Clock";
    var partialClasses = [''];

    // set options for window
    wnd_options.title = this.title;
    wnd_options.allowSettings = false;
    wnd_options.resizable = false;
    wnd_options.width = 150;
    wnd_options.height = 130;

    this.create(id, wnd_options, partialClasses);

}

clockWidget.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
*
*/
// Overwriting the index function!
clockWidget.prototype.index = function() {

    // load the first page upon start

    var url = SITE_URL+'/widget/' + this.widgetName + '/clock/index/' + this.id;
    ajaxRequests.load(this.id, url, "setWindowContent");

}
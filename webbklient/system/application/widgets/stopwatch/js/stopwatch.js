
function stopwatchWidget(id, wnd_options) {

    this.widgetName = "stopwatch";
    this.title = "Stopwatch";
    var partialClasses = [''];

    // set options for window
    wnd_options.title = this.title;
    wnd_options.allowSettings = false;
    wnd_options.resizable = false;
    wnd_options.width = 150;
    wnd_options.height = 180;

    this.create(id, wnd_options, partialClasses);

}

stopwatchWidget.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
*
*/
// Overwriting the index function!
stopwatchWidget.prototype.index = function() {

    // load the first page upon start

    var url = SITE_URL+'/widget/' + this.widgetName + '/stopwatch/index/' + this.id;
    ajaxRequests.load(this.id, url, "setWindowContent");

}
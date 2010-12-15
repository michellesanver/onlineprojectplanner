function Widget(id, wnd_options) {
	
	this.id = id;
	
	var initialContent = "<div class=\"widget_window\" id=\"widget_" + id + "\"></div>";
	
	wnd_options.content = initialContent;
	
	this.wnd = $('#content').window(wnd_options);
	//this.wnd.setFooterContent("<a href='#'><img src='"+BASE_URL+"images/buttons/small_setting.jpg' alt='Settings' /></a>");

}

Widget.prototype.setContent = function(data) {
	$('#widget_' + this.id).html(data);
}

Widget.prototype.getWindowObject = function() {
	return this.wnd;
};

function WIWindowHandler(options) {

	this.wnd = $('#content').window(options);
	this.wnd.setFooterContent("<a href='#'><img src='"+BASE_URL+"images/buttons/small_setting.jpg' alt='Settings' /></a>");

}
	
WIWindowHandler.prototype.getWindowObject = function() {
	return this.wnd;
};

function WIWindowHandler(options) {

	this.wnd = $('#content').window(options);
	this.wnd.setFooterContent("<a href='#'>asd</a>");

}
	
WIWindowHandler.prototype.getWindowObject = function() {
	return this.wnd;
};

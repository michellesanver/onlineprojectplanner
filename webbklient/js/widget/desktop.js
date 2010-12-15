Desktop = {
	
	_widgetArray : new Array(),
	
	newWidgetWindow : function() {
	
		var id = this._widgetArray.length -1;
		
		var widget = new Widget(id,
		{
			// change theese as needed
			title: browserWidget.widgetTitle,
			width: 800,
			height: 450,
			x: 30,
			y: 15,
		
			// do NOT change theese
			onMinimize:  this.onMinimize, 
			onClose:  this.onClose,
			checkBoundary: true,
			maxWidth: $('#content').width(),
			maxHeight: $('#content').height(),
			bookmarkable: false
		});
		
		return id;
	},
	
	setWidgetContent : function(id, data) {
		this._widgetArray[id].setContent(data);
	}
	
}

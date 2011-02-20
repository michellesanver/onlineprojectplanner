
/**
* Constructor for widget Wiki.
* Read more at https://code.google.com/p/onlineprojectplanner/
*
* @author Fredrik Johansson <tazzie76@gmail.com>
*/
function wikiWidget(id, wnd_options) {

		var partialClasses = ['wiki_main_content'];

		// set parameters for instance
        this.widgetName = "wiki";
        this.title = "Wiki 1.1";
		this.partialContentDivClass = partialClasses[0]; // used in prototype function loadUrl and showAjaxLoader
		this.errorIcon = BASE_URL+'images/backgrounds/erroricon.png'; // used in prototype function getContent_PageNotFound and getContent_NotAuthorized   
		
        // set options for window
        wnd_options.title = this.title;
        wnd_options.allowSettings = false;
        wnd_options.width = 650;
        wnd_options.height = 425;

		// create window and load first content
        this.create(id, wnd_options, partialClasses);
}

/**
* Add inheritance to Wiki from base
*/
wikiWidget.Inherits(Widget);

/**
* Override index-function that sets the first content
*/
wikiWidget.prototype.index = function() {
    var urlToLoad = SITE_URL+'/widget/' + this.widgetName + '/pages/index/' + this.id;
    ajaxRequests.load(this.id, urlToLoad, 'setWindowContent');
};

/**
* Common function to load a url from an a href-link
*
* @param object parameters {url(string), partial(bool)}
*/
wikiWidget.prototype.loadURL = function(parameters) {
	// prepare url
	var urlToLoad = SITE_URL+'/widget/' + this.widgetName + parameters.url;
	
	// load into partial or not?
	if (parameters.partial != undefined && parameters.partial === true) {
		this.showAjaxLoader();
		ajaxRequests.load(this.id, urlToLoad, 'common_callback', this.partialContentDivClass);
	} else {
		ajaxRequests.load(this.id, urlToLoad, 'common_callback');
	}
};

/**
* Show an AJAX loader manually (load partial content)
*/
wikiWidget.prototype.showAjaxLoader = function() {
	container = $('.' + this.partialContentDivClass);
	var loadingHTML = "<div class='frame_loading'>Loading...</div>"; 
	container.html(loadingHTML);
	var loading = container.children(".frame_loading");
	loading.css("marginLeft",    '-' + (loading.outerWidth() / 2) -20 + 'px');
};

/**
* Custom function to handle messages from wiki controller
* when a page is loaded or posted
*/
wikiWidget.prototype.common_callback = function(args) {
	
	// is result an array?
	if ( $.isArray(args) ) {
		
		// any message specific to wiki?
		if (args[0] == 'PAGE NOT FOUND') {
		
			// create new content
			args = new Array();
			args[0] = this.getContent_PageNotFound();
			args[1] = this.partialContentDivClass;
			
		} else if (args[0] == 'NOT AUTHORIZED') {
		
			// create new content
			args = new Array();
			args[0] = this.getContent_NotAuthorized();
			args[1] = this.partialContentDivClass;
		} 
	} 
	
	// set content with default function
	this.setWindowContent(args);
};

/**
* Return HTML-view for "page not found"
*/
wikiWidget.prototype.getContent_PageNotFound = function() {
    return '<h1>Error 404</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+this.errorIcon+'" /></span>The requested Wiki-page was not found.';
};

/**
* Return HTML-view for "not authorized"
*/    
wikiWidget.prototype.getContent_NotAuthorized = function() {
    return '<h1>Error 401</h1><span style="float:left;margin:5px;margin-top:-10px;"><img src="'+this.errorIcon+'" /></span>Authorization failed! You must be logged in.';             
};


/**
 * Common function to do a post with data from a form
 *
 * @param object parameters {url(string), partial(bool; default false), form_class(string)}
 */
wikiWidget.prototype.post = function(parameters) {
        
       var into_page_content = (parameters.partial != undefined ? parameters.partial : false);  
        
        // get form data
        var postdata = $('#' + this.divId).find('.' + parameters.form_class).serialize();
       
        // prepare url; add instance id 
        url = SITE_URL + '/widget/' + this.widgetName + parameters.url + '/' + this.id;
        
        // do post
        if (into_page_content == true) {
            this.showAjaxLoader();    
            ajaxRequests.post(this.id, postdata, url, "common_callback", this.partialContentDivClass);
        } else {
            ajaxRequests.post(this.id, postdata, url, "common_callback");                
        }
        
        // if this function is used in event onsubmit
        return false;
};

/**
 * A post-function "with a twist" for delete image in wysiwyg
 */
wikiWidget.prototype.postDeleteImage = function(parameters) {

        /*
         * step 1 of image removal; demand user to confirm delete
         */

        // use plugin jConfirm to create a common confirm dialog with jquery ui
        var options = {
            'question': 'Are you absolutely sure you want to delete the image "' + parameters.filename + '"? Action is permanent.',
            'title': 'Confirm delete',
            'callback_function': 'delete_image_callback',
            'widget_project_id': this.id,
            'height': 200,
            'width': 500
        };
        
        // create dialog
        $.jconfirm(options);
        
        // save parameters object for callback if confirmed
        this.postDeleteImage.saved_params = parameters;
};


/**
 * callback from postDeleteImage on confirmed
 *
 * @param mixed args
 */
wikiWidget.prototype.delete_image_callback = function(args) {
  
        /*
         * step 2 of image removal; show processing and send ajax-call
         */
        
        // inject processing html-element into document and display
        $.jprocessing( { 'title':'Processing', 'message': 'Please wait while removing image...', 'dialog_id': 'wiki-delete-image-processing' } ); 
        
        // prepare ajax call to delete image
        var parameters = this.postDeleteImage.saved_params;
        
        var postdata = {
                        'filename': parameters.filename,
                        'token': parameters.token,
                        'instance_id': this.id
        };
        
        var url = SITE_URL + '/widget/wiki/pages/delete_image';
        
        // send request (always partial)
        ajaxRequests.post(this.id, postdata, url, "delete_image_finished_callback", true);    
};

/**
 * callback from ajax request in delete_image_callback
 */
wikiWidget.prototype.delete_image_finished_callback = function(args) {
        
        /*
         * step 3 of image removal; handle result from ajax call
         */
        
        var parameters = this.postDeleteImage.saved_params;
        
        // close dialog processing
        $.jprocessing( "close" );
        
        // get response       
        var response;
        if ( $.isArray(args) ) {
            response = args[0];
        } else {
            response = args;
        }
        
        var title = "";
        var message = "";
        
        // ok or error?
        if (response == 'Error') {
            // show result as error
            title = "Error";
            message = "Unable to remove image.";
            
        } else {
            // show result as ok
            title = "Success";
            message = "Image has been removed.";
           
            // run callback on success (global function on 'page' and 'create')
            eval( parameters.callback_sucess + '({ "remove_id": "' + parameters.callback_parameters.remove_id + '", "filename": "'+ parameters.callback_parameters.filename +'" });' );
        }
        
        // inject result html-element into document and display
        $.jresult( { 'title': title, 'message': message } );
        
};

/**
 * Search for a tag by freetext word or specific tag
 */
wikiWidget.prototype.search = function(parameters) {
  
        // get data to post
        var postdata = {'word': parameters.word, 'tag': parameters.tag };
  
        // prepare url and also add instance id 
        var url = SITE_URL+'/widget/'+this.widgetName+ '/pages/search/' + this.id;
       
       // show ajax spinner
       this.showAjaxLoader();
       
       // send request (always partial)
       ajaxRequests.post(this.id, postdata, url, "common_callback", this.partialContentDivClass); 
       
       return false; 
};


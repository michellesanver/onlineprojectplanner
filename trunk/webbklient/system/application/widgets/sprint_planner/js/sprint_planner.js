/* 
* Name: AJAX Template
* Desc: A widget created only to be used as an example of how to create a widget.
* Last update: 3/2-2011 by Dennis Sangmo
*/
function sprint_planner(id, wnd_options) {
	this.widgetName = "sprint_planner";
	this.title = "Sprint Planner";
	var partialClasses = [];
	
	// set options for window
	wnd_options.title = this.title;
	wnd_options.allowSettings = true;
	wnd_options.width = 800;
	wnd_options.height = 450;
	
	// Add settings event listener
	Desktop.settingsEvent.addSettingsEventListener(id, "settingsEventTest");
	
	this.create(id, wnd_options, partialClasses);
}

sprint_planner.Inherits(Widget);

/*
* Here comes all happening function. Executed from html links
* 
*/
// Overwriting the index function!
sprint_planner.prototype.index = function() {
	// load the first page upon start
	var url = SITE_URL+'/widget/' + this.widgetName + '/sprint_planner_controller/index/' + this.id;
	ajaxRequests.load(this.id, url, "init");
}

/*
sprint_planner.prototype.replot = function(that) {
	var url = SITE_URL+'/widget/'+that.widgetName + '/sprint_planner_controller/get_all_points/' + that.id;
	ajaxRequests.load(that.id, url, "plotburndown", true);
}
*/

sprint_planner.prototype.plotsprintburndown = function(sprintid, that) {
	var url = SITE_URL+'/widget/'+that.widgetName + '/sprint_planner_controller/get_all_sprint_points/' + that.id + '/' + sprintid;
	ajaxRequests.load(that.id, url, "plotburndown", true);
}

sprint_planner.prototype.plotburndown = function(data) {
	$.jqplot.config.enablePlugins = true;
    			
		var points = jQuery.parseJSON(data);
		
		if(points == 0) {
			return false;
		}
		
		var allPoints = points.points;
		var days = points.days;
		var chartid = 'sprintchart';
		
	    plot1 = $.jqplot(chartid, [allPoints], {
	        legend: {
	            show: true
	        },
	        grid: {
	            background: '#f3f3f3',
	            gridLineColor: '#cccccc'
	        },
	        series: [{
	            label: 'Burndown'
	        }],
	
	        axes: {
	            xaxis: {
	                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
	                rendererOptions: {
	                    tickRenderer: $.jqplot.CanvasAxisTickRenderer
	                },
	                label: 'Days',
	                min: 1,
	                max: days,
	                numberTicks: days,
	                tickOptions: {
	                    formatString: 'Day %d',
	                     showLabel: false
	                }
	            },
	
	            yaxis: {
	                labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
	                label: 'Points',
	                min: 0,
	                numberTicks: 5,
	                tickOptions: {
	                    formatString: '%d',
	                    showLabel: false,
	                }
	            }
	        },
	        highlighter: {
	            sizeAdjust: 7.5
	        },
	        cursor: {
	            zoom: true,
	            showTooltip: false
	        }
	    });
	    
	    plot1.replot();
}

sprint_planner.prototype.init = function(data) {
	// Save this to that, so that we can refer to everything in this when this is no longer this! ;)
	var that = this;
	sprint_planner.prototype.loadSprints(that);
	
	// Plot
	//sprint_planner.prototype.replot(that);
	
	// Load the data into the correct div-id
	$('#' + this.divId).html(data);
	
	// Use jQuery UI tabs.
	$('#' + this.divId).find("#tabs").tabs();
	 
	// When we change tabs, replot!
    $('#' + this.divId).find("#tabs").bind('tabsshow', function(event, ui) {
    	//sprint_planner.prototype.replot(that);
    	sprint_planner.prototype.loadSprints(that);
    });
    
    // Destroy dialog.
    $('#' + this.divId).find("#dialog:ui-dialog").dialog("destroy");
	
	
    var story = $('#' + this.divId).find("#story"),
        points = $('#' + this.divId).find("#points"),
        assignee = $('#' + this.divId).find("#assignee"),
        description = $('#' + this.divId).find("#description"),
        allFields = $([]).add(story).add(points).add(assignee).add(description),
        tips = $('#' + this.divId).find(".validateTips"),
        sprintname = $('#' + this.divId).find("#sprintname"),
        sprintdays = $('#' + this.divId).find("#sprintdays"),
        days = $('#' + this.divId).find("#sprint_planner_points_days"),
        story_id = $('#' + this.divId).find("#sprint_planner_points_story_id"),
        add_story_to_sprint_id = $('#' + this.divId).find("#add_story_to_sprint_id"),
        day1 = $('#' + this.divId).find("#day1"),
        add_story_to_sprint_story_id = $('#' + this.divId).find("#add_story_to_sprint_story_id"); 
        
	var edit_story_name = $('#' + this.divId).find("#edit_story_name"),
        edit_points = $('#' + this.divId).find("#edit_points"),
        edit_assignee = $('#' + this.divId).find("#edit_assignee"),
        edit_story_id = $('#' + this.divId).find("#edit_story_id"),
        edit_description = $('#' + this.divId).find("#edit_description"),
        allFields = $([]).add(edit_story_name).add(edit_points).add(edit_story_id).add(edit_assignee).add(edit_description),
        tips = $(".validateTips");
                
    function updateTips(t) {
        tips.text(t).addClass("ui-state-highlight");
        setTimeout(function() {
            tips.removeClass("ui-state-highlight", 1500);
        }, 500);
    }
	
	function isNumber(o, n ) {
		if(o.val() != parseInt(o.val())) {
			o.addClass("ui-state-error");
            updateTips(n + " has to be numeric.");
			return false;
		} else {
			return true;
		}
	}
	
    function checkLength(o, n, min, max) {
        if (o.val().length > max || o.val().length < min) {
            o.addClass("ui-state-error");
            updateTips("Length of " + n + " must be between " + min + " and " + max + ".");
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp(o, regexp, n) {
        if (!(regexp.test(o.val()))) {
            o.addClass("ui-state-error");
            updateTips(n);
            return false;
        } else {
            return true;
        }
    }
	
	//Add story
    $("#story-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 500,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Add story": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");
                bValid = bValid && checkLength(story, "story", 3, 16);
                bValid = bValid && checkLength(description, "description", 6, 80);
                bValid = bValid && isNumber(points, "Points");

                if (bValid) {                	
                	var postdata = {'story': story.val(), 'assignee': assignee.val(), 'description': description.val(), 'points': points.val(), 'project_widget_id':that.id };
                	var url = SITE_URL + '/widget/' + that.widgetName + '/sprint_planner_controller/add_story';
                	ajaxRequests.post(that.id, postdata, url, "refreshStories", true);
                	
                	story.val("");
                	assignee.val("");
                	description.val("");
                	points.val("");
                	
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
            
            	story.val("");
            	assignee.val("");
            	description.val("");
            	points.val("");
                	
                $(this).dialog("close");
            }
        },
        close: function() {
            allFields.val("").removeClass("ui-state-error");
        }
    });
	
	
	// Delete sprint
    $("#delete-sprint-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Delete sprint": function() {
                var bValid = true;

                if (bValid) {

                    sprint_planner.prototype.deleteSprint(removesprintid, instance_id);
                    
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
    
    // Delete sprint
    $("#chart-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Done": function() {
                var bValid = true;

                if (bValid) {                    
                    $(this).dialog("close");
                }
            }
        },
        close: function() {
           
        }
    });
    
    // Delete sprint
    $("#remove-story-from-sprint-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Delete sprint": function() {
                var bValid = true;

                if (bValid) {                    
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
		
	//Edit story
    $("#edit-story-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 500,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Edit story": function() {
            	//alert(edit_story_id.val());
                var bValid = true;
                allFields.removeClass("ui-state-error");
                bValid = bValid && checkLength(edit_story_name, "story", 3, 16);
                bValid = bValid && checkLength(edit_description, "description", 6, 80);
                bValid = bValid && isNumber(edit_points, "Points");

                if (bValid) {

                    sprint_planner.prototype.editStory(edit_story_id.val(), edit_story_name.val(), edit_assignee.val(), edit_description.val(), edit_points.val(), that);
                    
                    
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
            allFields.val("").removeClass("ui-state-error");
        }
    });
    
    // Delete story
    $("#delete-story-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 350,
        modal: true,
        zIndex: 3999,
        buttons: {
            "Delete story": function() {
                var bValid = true;

                if (bValid) {

                    sprint_planner.prototype.deleteStory(document.getElementById('delete_story_id').value, that);
                    
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
    
    // Storypoints
    $("#points-story-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        zIndex: 3999,
        buttons: {
            "Done": function() {
                var bValid = true;

                if (bValid) {
                    var storyid = document.getElementById("sprint_planner_points_story_id").value;
                    var days = document.getElementById("sprint_planner_points_days").value;
                    
                    for(i = 1; i <= days; i++) {
                    	var daypoints = document.getElementById("day" + i).value;
                    	var url = SITE_URL + '/widget/' + that.widgetName + '/sprint_planner_controller/save_points';
                    	var postdata = {'story_id': storyid, 'day': i, 'daypoints': daypoints};
                		ajaxRequests.post(that.id, postdata, url, "loadSprints", true);
                		
                    }
                    
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
    
        
    // Add sprint
    $("#add-sprint-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        zIndex: 3999,
        buttons: {
            "Done": function() {
                var bValid = true;

                if (bValid) {
					var postdata = {'sprintname': sprintname.val(), 'days': sprintdays.val(), 'project_widget_id':that.id };
					
                	var url = SITE_URL + '/widget/' + that.widgetName + '/sprint_planner_controller/add_sprint';
                	ajaxRequests.post(that.id, postdata, url, "loadSprints", true);
                	
                	sprintdays.val("");
                	sprintname.val("");
                	
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
    
     // Add stories to sprint
    $("#add-story-to-sprint-dialog-form").dialog({
        resizable: false,
        autoOpen: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        zIndex: 3999,
        buttons: {
            "Done": function() {
                var bValid = true;

                if (bValid) {
					
					var postdata = {'sprint_id': add_story_to_sprint_id.val(), 'story_id': add_story_to_sprint_story_id.val()};
					
                	var url = SITE_URL + '/widget/' + that.widgetName + '/sprint_planner_controller/add_sprint_to_story';
                	ajaxRequests.post(that.id, postdata, url, "loadSprints", true);
                	
                    $(this).dialog("close");

                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
           
        }
    });
    
    $("#create-story").button().click(function() {
        $("#story-dialog-form").dialog("open");
    });
    
    $("#add-sprint-button").button().click(function() {
        $("#add-sprint-dialog-form").dialog("open");
    });
	
	$("#add-story-to-sprint-button").button().click(function() {
        $("#add-story-to-sprint-dialog-form").dialog("open");
    });
    
	//Render stories
	sprint_planner.prototype.renderStories(that);
	
	//Render sprints
	sprint_planner.prototype.loadSprints(that);

}

// Render stories
sprint_planner.prototype.renderStories = function(that) {

	if(that.widgetName == undefined) {
		that = this;
	}

	var url = SITE_URL+'/widget/' + that.widgetName + '/sprint_planner_controller/getAllStories/' + that.id;
	ajaxRequests.load(that.id, url, "renderstories_callback", true);
}

// Callback to actually render the stories.
sprint_planner.prototype.renderstories_callback = function(data) {
	var that = this;
	var storybody = document.getElementById("story_table_content");
	
	var stories = jQuery.parseJSON(data);
	
	if (storybody.hasChildNodes()) {
	    while (storybody.childNodes.length >= 1 )
	    {
	        storybody.removeChild(storybody.firstChild );       
	    } 
	}
		
	for(index in stories) {
		var story = stories[index].Name;
		var assignee = stories[index].Assignee;
		var storyId = stories[index].Stories_id;
		var totalPoints = stories[index].Total_points;
		var description = stories[index].Description;
		
		var trNode = document.createElement("tr");
		
		// Story
		var nameTdNode = document.createElement("td");
		var nameNode = document.createTextNode(story)
		nameTdNode.appendChild(nameNode);
		
		// Assignee
		var assigneeTdNode = document.createElement("td");
		var assigneeNode = document.createTextNode(assignee)
		assigneeTdNode.appendChild(assigneeNode);
		
		// Description
		var descriptionTdNode = document.createElement("td");
		var descriptionNode = document.createTextNode(description)
		descriptionTdNode.appendChild(descriptionNode);
		
		// Points
		var totalPointsTdNode = document.createElement("td");
		var pointsNode = document.createTextNode(totalPoints)
		totalPointsTdNode.appendChild(pointsNode);
		
		// Edit
		var buttonTdNode = document.createElement("td");
		
		var editbuttonNode = document.createElement("button");
		var editbuttonText = document.createTextNode("Edit");
		editbuttonNode.appendChild(editbuttonText);
		
		editbuttonNode.className = "edit";
		editbuttonNode.id = storyId;
		editbuttonNode.onclick = function() {
			
			var fieldNode = document.createElement("fieldset");
		
			var url = SITE_URL+'/widget/sprint_planner/sprint_planner_controller/getStory/' + this.id;
			ajaxRequests.load(that.id, url, "generateEditForm_callback", true);
		}
		
		// Delete			
		buttonTdNode.appendChild(editbuttonNode);
		
		var deletebuttonNode = document.createElement("button");
		var deletebuttonText = document.createTextNode("Delete");
		deletebuttonNode.appendChild(deletebuttonText);
		
		deletebuttonNode.className = "delete";
		deletebuttonNode.id = storyId;
		deletebuttonNode.name = this.id;
		deletebuttonNode.onclick = sprint_planner.prototype.deleteStoryDialog;
		buttonTdNode.appendChild(deletebuttonNode);
		
		// Points			
		/*

		var pointsbuttonNode = document.createElement("button");
		var pointsbuttonText = document.createTextNode("Points");
		pointsbuttonNode.appendChild(pointsbuttonText);
		
		pointsbuttonNode.className = "points";
		pointsbuttonNode.id = storyId;
		pointsbuttonNode.name = this.id;
		pointsbuttonNode.onclick = sprint_planner.prototype.pointsStoryDialog;
*/
						
		//buttonTdNode.appendChild(pointsbuttonNode);
		
		trNode.appendChild(nameTdNode);
		trNode.appendChild(assigneeTdNode);
		trNode.appendChild(descriptionTdNode);
		trNode.appendChild(totalPointsTdNode);
		trNode.appendChild(buttonTdNode);
		
		storybody.appendChild(trNode);
	}
	
	
}

// Delete a story
sprint_planner.prototype.deleteStoryDialog = function() {
	document.getElementById('delete_story_id').value = this.id;
	$("#delete-story-dialog-form").dialog("open");
}

sprint_planner.prototype.deleteStory = function(story_id, that) {
	url = SITE_URL +'/widget/' + that.widgetName + '/sprint_planner_controller/delete_story';

	// prepare data to send
    var postdata = {'story_id': story_id };
       
    // send request
    ajaxRequests.post(that.id, postdata, url, "refreshStories", true);
}



// Refresh stories
sprint_planner.prototype.refreshStories = function(data) {
	sprint_planner.prototype.renderStories(this);
}

// Load/refresh all sprints
sprint_planner.prototype.loadSprints = function(that) {	
	if(that.id === undefined) {
		that = this;
	}
	
	var url = SITE_URL+'/widget/'+that.widgetName + '/sprint_planner_controller/get_all_sprints/' + that.id;
	ajaxRequests.load(that.id, url, "loadSprints_callback", true);
	
}

sprint_planner.prototype.editStory = function(story_id, story, assignee, description, points, that) {
		url = SITE_URL +'/widget/' + that.widgetName + '/sprint_planner_controller/edit_story';
		
		// prepare data to send
       var postdata = {'story_id': story_id, 'story': story, 'assignee': assignee, 'description': description, 'points': points, 'project_widget_id':that.id};
                                
       // send request
       ajaxRequests.post(that.id, postdata, url, "renderStories", true);
}

sprint_planner.prototype.generateEditForm_callback = function(data) {
	var story = jQuery.parseJSON(data);
	
	document.getElementById('edit_story_name').value = story.Name;
	document.getElementById('edit_story_id').value = story.Stories_id;
	document.getElementById('edit_description').value = story.Description;
	document.getElementById('edit_points').value = story.Total_points;
	
	$("#edit-story-dialog-form").dialog("open");
}

sprint_planner.prototype.generatePointsForm = function(story_id, days, instance_id) {	
		var fieldNode = document.createElement("fieldset");
		
		var url = SITE_URL+'/widget/sprint_planner/sprint_planner_controller/get_points/' + story_id + '/' + days;
		ajaxRequests.load(instance_id, url, "generatePointsForm_callback", true);
}

sprint_planner.prototype.generatePointsForm_callback = function(data) {
	var points = jQuery.parseJSON(data);
	
	//$("#points-story-dialog-form").dialog("destroy");
	var pointsForm = document.getElementById('sprint_planner_points_form');
	
	if (pointsForm.hasChildNodes() )
	{
	    while (pointsForm.childNodes.length >= 1 )
	    {
	        pointsForm.removeChild(pointsForm.firstChild );       
	    } 
	}
	
	var fieldNode = document.createElement("fieldset");
	var days = 0;
	
	for(index in points) {
		days++;
	}
	
	for(index in points) {
			var story_id = points[index].Story_id;
			var fortext = document.createTextNode('Day ' + index);
			var labelx = document.createElement("label");
			labelx.setAttribute('for', 'day'+index);
			labelx.appendChild(fortext);
			
			var inputx = document.createElement("input");
			inputx.setAttribute('type', 'text');
			inputx.setAttribute('name', 'day'+index);
			inputx.setAttribute('id', 'day'+index);
			inputx.setAttribute('maxlength', 5);
			inputx.setAttribute('size', 5);
			inputx.setAttribute('value', points[index].Points_done);
			inputx.setAttribute('class', 'text ui-widget-content ui-corner-all');
			
			var storyinput = document.createElement("input");
			storyinput.setAttribute('type', 'hidden');
			storyinput.setAttribute('id', 'sprint_planner_points_story_id');
			storyinput.setAttribute('value', story_id);
			
			var daysinput = document.createElement("input");
			daysinput.setAttribute('type', 'hidden');
			daysinput.setAttribute('id', 'sprint_planner_points_days');
			daysinput.setAttribute('value', days);
			
			fieldNode.appendChild(labelx);
			fieldNode.appendChild(inputx);
			fieldNode.appendChild(storyinput);
			fieldNode.appendChild(daysinput);
	}
	
	pointsForm.appendChild(fieldNode);
	var storyid = document.getElementById("sprint_planner_points_story_id").value;
	$("#points-story-dialog-form").dialog("open");
	
}

sprint_planner.prototype.generateSprintTable = function(sprintid, instance_id) {	
	var url = SITE_URL+'/widget/sprint_planner/sprint_planner_controller/get_stories_in_sprint/' + sprintid + '/' + instance_id;
	ajaxRequests.load(instance_id, url, "generateSprintTable_callback", true);
}

sprint_planner.prototype.generateSprintTable_callback = function(data) {	
	//var table = document.getElementById("sprint_table_" + );
	var that = this;
	var storydata = jQuery.parseJSON(data);
	var stories = storydata['stories'];
	var allstories = storydata['allstories'] 
	var table;
	
	if(allstories != null) {
		var storydropdown = document.getElementById("add_story_to_sprint_story_id");
		
		if (storydropdown.hasChildNodes()) {
		    while (storydropdown.childNodes.length >= 1 )
		    {
		        storydropdown.removeChild(storydropdown.firstChild );       
		    } 
		}
	
		for(index in allstories) {
			var optionNode = document.createElement("option");
			optionNode.value = allstories[index].Stories_id;
			optionNode.text = allstories[index].Name;
			storydropdown.appendChild(optionNode);
			
		}
	
	}
	
	
	if(stories != null) {
		// Define table and empty stories
		for(index in stories) {
			// Get the table
			table = document.getElementById("sprint_table_" + stories[index].Sprint_id);
			
			// Remove children
			if (table.hasChildNodes()) {
			    while (table.childNodes.length >= 1 )
			    {
			        table.removeChild(table.firstChild );       
			    } 
			}
		}
		
		
		
		table.className = "ui-widget sprint_table";
		
		// Paint out the headers
		var headerHeadNode = document.createElement("thead");
		headerHeadNode.className = "ui-widget-header";
		
		var headerNode = document.createElement("tr");
		
		var headerDoneNode = document.createElement("th");
		
		var headerNameNode = document.createElement("th");
		var headerName = document.createTextNode("Name");
		headerNameNode.appendChild(headerName);
		
		var headerDescriptionNode = document.createElement("th");
		var headerDescription = document.createTextNode("Description");
		headerDescriptionNode.appendChild(headerDescription);
		
		var headerAssigneeNode = document.createElement("th");
		var headerAssignee = document.createTextNode("Assignee");
		headerAssigneeNode.appendChild(headerAssignee);
		
		var headerPointsNode = document.createElement("th");
		var headerPoints = document.createTextNode("Points");
		headerPointsNode.appendChild(headerPoints);
		
		var headerButtonsNode = document.createElement("th");
		
		// Append to table
		headerNode.appendChild(headerDoneNode);
		headerNode.appendChild(headerNameNode);
		headerNode.appendChild(headerDescriptionNode);
		headerNode.appendChild(headerAssigneeNode);
		headerNode.appendChild(headerPointsNode);
		headerNode.appendChild(headerButtonsNode);
		
		headerHeadNode.appendChild(headerNode);
		table.appendChild(headerHeadNode);
		
		// Body
		var bodyNode = document.createElement("tbody");
		bodyNode.className = "ui-widget-content";
				
		// Paint it out
		for(index in stories) {
		
			var storyId = stories[index].Stories_id;
			var storyName = stories[index].Name;
			
			var trNode = document.createElement("tr");
			
			var doneTdNode = document.createElement("td");
			var doneNode = document.createElement("input");
			doneNode.type = "checkbox";
			doneNode.id = storyId;
			
			if(stories[index].Is_done == 'true') {
				doneNode.checked = 'checked';
			}
			
			doneNode.onchange = function() {
				alert(this.id);
				alert(this.checked);
				
				var postdata = {'story_id': this.id, 'checked': this.checked};
				
            	var url = SITE_URL + '/widget/sprint_planner/sprint_planner_controller/story_change_done';
            	ajaxRequests.post(that.id, postdata, url, "loadSprints", true);
			}
			
			doneTdNode.appendChild(doneNode);
			
			var nameTdNode = document.createElement("td");
			var nameNode = document.createTextNode(stories[index].Name);
			nameTdNode.appendChild(nameNode);
			
			var descriptionTdNode = document.createElement("td");
			var descriptionNode = document.createTextNode(stories[index].Description);
			descriptionTdNode.appendChild(descriptionNode);

			var assigneeTdNode = document.createElement("td");
			var assigneeNode = document.createTextNode(stories[index].Assignee);
			assigneeTdNode.appendChild(assigneeNode);
			
			var pointsTdNode = document.createElement("td");
			var pointsNode = document.createTextNode(stories[index].Total_points);
			pointsTdNode.appendChild(pointsNode);
			
			// Points
			var buttonTdNode = document.createElement("td");
			buttonTdNode.className = "buttonstd";
			
			var pointsbuttonNode = document.createElement("button");
			var pointsbuttonText = document.createTextNode("Points");
			pointsbuttonNode.appendChild(pointsbuttonText);
			
			pointsbuttonNode.className = "points";
			pointsbuttonNode.id = storyId;
			pointsbuttonNode.name = stories[index].Days;
			pointsbuttonNode.title = stories[index].Instance_id;
			
			pointsbuttonNode.onclick = function() {
				sprint_planner.prototype.generatePointsForm(this.id, this.name, this.title);
			}
						
			buttonTdNode.appendChild(pointsbuttonNode);
			
			// Delete
			var deletebuttonNode = document.createElement("button");
			var deletebuttonText = document.createTextNode("Delete");
			deletebuttonNode.appendChild(deletebuttonText);
			
			deletebuttonNode.className = "delete";
			deletebuttonNode.id = storyId;
			deletebuttonNode.name = this.id;
			
			deletebuttonNode.onclick = function() {
            	var storyid = this.id;
            	var instance_id = this.name;
            	
            	$("#remove-story-from-sprint-form").dialog({
			        resizable: false,
			        autoOpen: false,
			        height: 200,
			        width: 350,
			        modal: true,
			        zIndex: 3999,
			        buttons: {
			            "Delete sprint": function() {
			                var bValid = true;
			
			                if (bValid) { 
			                	var postdata = {'sprint_id': 'delete', 'story_id': storyid};
				
				            	var url = SITE_URL + '/widget/sprint_planner/sprint_planner_controller/add_sprint_to_story';
				            	ajaxRequests.post(instance_id, postdata, url, "loadSprints", true);
            	                   
			                    $(this).dialog("close");
			                }
			            },
			            Cancel: function() {
			                $(this).dialog("close");
			            }
			        },
			        close: function() {
			           
			        }
			    });
				
				$("#remove-story-from-sprint-form").dialog("open");
			}
			
			buttonTdNode.appendChild(deletebuttonNode);
			
			trNode.appendChild(doneTdNode);
			trNode.appendChild(nameTdNode);
			trNode.appendChild(descriptionTdNode);
			trNode.appendChild(assigneeTdNode);
			trNode.appendChild(pointsTdNode);
			trNode.appendChild(buttonTdNode);
			
			bodyNode.appendChild(trNode);
			
			
		}
		
		table.appendChild(bodyNode);
	}
}

sprint_planner.prototype.loadSprints_callback = function(data) {
	$('#' + this.divId).find("#sprintsaccordion").accordion("destroy");
	var that = this;
	var sprintbody = document.getElementById("sprintsaccordion");
	var sprints = jQuery.parseJSON(data);
	
	if (sprintbody.hasChildNodes()) {
	    while (sprintbody.childNodes.length >= 1 )
	    {
	        sprintbody.removeChild(sprintbody.firstChild );       
	    } 
	}
	
	var sprintdropdown = document.getElementById("add_story_to_sprint_id");
	
	// Remove children
	if (sprintdropdown.hasChildNodes()) {
	    while (sprintdropdown.childNodes.length >= 1 )
	    {
	        sprintdropdown.removeChild(sprintdropdown.firstChild );       
	    } 
	}
    var counter = 0;             
	for(index in sprints) {
		var sprintname = sprints[index].Sprint_name;
		var sprintid = sprints[index].Sprint_id;
		var instance_id = sprints[index].Instance_id;
	
		var optionNode = document.createElement("option");
		optionNode.value = sprintid;
		optionNode.text = sprintname;
		sprintdropdown.appendChild(optionNode);
		
		// H3 node
		var h3Node = document.createElement("h3");
		var aNode = document.createElement("a");
		aNode.href = "#";
		aNode.id = counter;

		//aNode.
		var titleNode = document.createTextNode(sprintname);
		aNode.appendChild(titleNode);
		h3Node.appendChild(aNode);
		
		var sprintDivNode = document.createElement("div");
		
		var removeButtonNode = document.createElement("button");
		var removeButtonText = document.createTextNode("Remove sprint");
		removeButtonNode.appendChild(removeButtonText);
		removeButtonNode.id = sprintid;
		
		removeButtonNode.onclick = function() {
			var removesprintid = this.id;
			
			// Delete story
		    $("#delete-sprint-dialog-form").dialog({
		        resizable: false,
		        autoOpen: false,
		        height: 200,
		        width: 350,
		        modal: true,
		        zIndex: 3999,
		        buttons: {
		            "Delete sprint": function() {
		                var bValid = true;
		
		                if (bValid) {
		
		                    sprint_planner.prototype.deleteSprint(removesprintid, instance_id);
		                    
		                    $(this).dialog("close");
		                }
		            },
		            Cancel: function() {
		                $(this).dialog("close");
		            }
		        },
		        close: function() {
		           
		        }
		    });
			
			$("#delete-sprint-dialog-form").dialog("open");
		}
		
		var chartButtonNode = document.createElement("button");
		var chartButtonText = document.createTextNode("View burndown");
		chartButtonNode.appendChild(chartButtonText);
		chartButtonNode.id = sprintid;
		
		chartButtonNode.onclick = function() {
			sprint_planner.prototype.plotsprintburndown(this.id, that);
			
			$("#chart-form").dialog({
		        resizable: false,
		        autoOpen: false,
		        height: 500,
		        width: 750,
		        modal: true,
		        zIndex: 3999,
		        buttons: {
		            "Done": function() {
		                var bValid = true;
		
		                if (bValid) {                    
		                    $(this).dialog("close");
		                }
		            }
		        },
		        close: function() {
		           
		        }
		    });			
			$("#chart-form").dialog("open");
		}

        var chartNode = document.createElement("div");
        chartNode.id = "sprintchart_" + sprintid;
        chartNode.setAttribute("data-height", "260px");
        chartNode.setAttribute("data-width", "600px");
        chartNode.setAttribute("data-width", "600px");
		
		var sprint_table = document.createElement("table");
		sprint_table.id = 'sprint_table_' + sprintid;
		sprint_table.className = 'sprint_table';
		
		sprintDivNode.appendChild(removeButtonNode);
		sprintDivNode.appendChild(chartButtonNode);
		sprintDivNode.appendChild(sprint_table);
		sprintDivNode.appendChild(chartNode);
		
		sprintbody.appendChild(h3Node);
		sprintbody.appendChild(sprintDivNode);
		//sprintbody.appendChild(sprint_table);
		
		
		sprint_planner.prototype.generateSprintTable(sprintid, instance_id);
		sprint_planner.prototype.plotsprintburndown(sprintid, this);
		counter++;
	}
	
	var accordion = $("#sprintsaccordion").accordion({autoHeight: false});
}


sprint_planner.prototype.deleteSprint = function(sprintid, instance_id) {	
	url = SITE_URL +'/widget/sprint_planner/sprint_planner_controller/delete_sprint';

	// prepare data to send
    var postdata = {'sprint_id': sprintid };
       
    // send request
    ajaxRequests.post(instance_id, postdata, url, "loadSprints", true);
}
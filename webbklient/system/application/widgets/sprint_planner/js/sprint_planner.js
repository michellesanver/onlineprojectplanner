// place widget in a namespace (javascript object simulates a namespace)
sprint_planner = {

    // widget specific settings
    sprint_planner_instance_id: null,
    days: 15,
    widgetTitle: 'Sprint Planner',
    widgetName: 'sprint_planner', // also name of folder
	allPoints: [0,0,0],
	currentPartial: null,
    
    // function that will be called upon start (REQUIRED - do NOT change the name)
    open: function(project_widget_id, widgetIconId, last_position) {
    		sprint_planner.sprint_planner_instance_id = project_widget_id;
			// set options for window
			var windowOptions = {
				// change theese as needed
				title: sprint_planner.widgetTitle,
				width: 800,
				height: 450
			};
	      
			// create window
			Desktop.newWidgetWindow(project_widget_id, windowOptions, widgetIconId, null, last_position);
			
			
			// load the first page upon start
            var loadFirstPage = SITE_URL+'/widget/' + sprint_planner.widgetName + '/sprint_planner_controller/index/' + sprint_planner.sprint_planner_instance_id;
			ajaxRequests.load(loadFirstPage, "sprint_planner.setContent", "sprint_planner.setAjaxError");
			
			$.get(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/get_days/' + sprint_planner.sprint_planner_instance_id, function(data) {
				data = parseInt(data);
			  	sprint_planner.days = data;
			});
			
			sprint_planner.replot();
			
			
		},
		
		
	addStory: function(url, story, assignee, description, points) {
		url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
		
		 // prepare data to send
        var postdata = {'story': story, 'assignee': assignee, 'description': description, 'points': points, 'project_widget_id':sprint_planner.sprint_planner_instance_id };
                                
        // send request
        ajaxRequests.post(postdata, url, 'sprint_planner.addStory_callback', 'sprint_planner.setAjaxError', true);
        
        
		
	},
	
	replot: function() {
		var JSONFile = $.getJSON(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/get_all_points/' + sprint_planner.sprint_planner_instance_id, sprint_planner.plotburndown);
			
		eval(JSONFile);
		
	},
	
	addStory_callback: function(data) {
	
		//Reload stories
		sprint_planner.renderStories();
    },
    
    plotburndown: function(data) {
    	$.jqplot.config.enablePlugins = true;
    	
    	sprint_planner.allPoints = [];
    			
		for(index in data) {
			sprint_planner.allPoints.push(data[index]);
		}
	
		
	    plot1 = $.jqplot('chart1', [sprint_planner.allPoints], {
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
	                max: sprint_planner.days+1,
	                numberTicks: sprint_planner.days+1,
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

    },
    
    editStory: function(url, story_id, story, assignee, description, points) {
    	url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
		
		 // prepare data to send
        var postdata = {'story_id': story_id, 'story': story, 'assignee': assignee, 'description': description, 'points': points, 'project_widget_id':sprint_planner.sprint_planner_instance_id };
                                
        // send request
        ajaxRequests.post(postdata, url, 'sprint_planner.editStory_callback', 'sprint_planner.setAjaxError', true);

    },
    
    editStory_callback: function(data) {
    	//Reload stories
		sprint_planner.renderStories();
    },
    
    
    deleteStory: function(url, story_id) {
    	url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
		
		 // prepare data to send
        var postdata = {'story_id': story_id };
           
        // send request
        ajaxRequests.post(postdata, url, 'sprint_planner.deleteStory_callback', 'sprint_planner.setAjaxError', true);

    },
    
    deleteStory_callback: function(data) {
    	//Reload stories
		sprint_planner.renderStories();
    },
	
	renderStories: function() {
		var storybody = document.getElementById("story_table_content");
		
		if (storybody.hasChildNodes() )
		{
		    while (storybody.childNodes.length >= 1 )
		    {
		        storybody.removeChild(storybody.firstChild );       
		    } 
		}

		var JSONFile = $.getJSON(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/getAllStories/' + sprint_planner.sprint_planner_instance_id, function(data) {
			for(index in data) {
				var story = data[index].Name;
				var assignee = data[index].Assignee;
				var storyId = data[index].Stories_id;
				var totalPoints = data[index].Total_points;
				var description = data[index].Description;
				
				var trNode = document.createElement("tr");
				
				var nameTdNode = document.createElement("td");
				var nameNode = document.createTextNode(story)
				nameTdNode.appendChild(nameNode);
				
				var assigneeTdNode = document.createElement("td");
				var assigneeNode = document.createTextNode(assignee)
				assigneeTdNode.appendChild(assigneeNode);
				
				var descriptionTdNode = document.createElement("td");
				var descriptionNode = document.createTextNode(description)
				descriptionTdNode.appendChild(descriptionNode);
				
				var totalPointsTdNode = document.createElement("td");
				var pointsNode = document.createTextNode(totalPoints)
				totalPointsTdNode.appendChild(pointsNode);
				
				var buttonTdNode = document.createElement("td");
				
				var editbuttonNode = document.createElement("button");
				var editbuttonText = document.createTextNode("Edit");
				editbuttonNode.appendChild(editbuttonText);
				
				editbuttonNode.className = "edit";
				editbuttonNode.id = storyId;
				editbuttonNode.onclick = sprint_planner.editStoryDialog;
								
				buttonTdNode.appendChild(editbuttonNode);
				
				var deletebuttonNode = document.createElement("button");
				var deletebuttonText = document.createTextNode("Delete");
				deletebuttonNode.appendChild(deletebuttonText);
				
				deletebuttonNode.className = "delete";
				deletebuttonNode.id = storyId;
				deletebuttonNode.onclick = sprint_planner.deleteStoryDialog;
								
				buttonTdNode.appendChild(deletebuttonNode);
				
				var pointsbuttonNode = document.createElement("button");
				var pointsbuttonText = document.createTextNode("Points");
				pointsbuttonNode.appendChild(pointsbuttonText);
				
				pointsbuttonNode.className = "points";
				pointsbuttonNode.id = storyId;
				pointsbuttonNode.onclick = sprint_planner.pointsStoryDialog;
								
				buttonTdNode.appendChild(pointsbuttonNode);
				
				trNode.appendChild(nameTdNode);
				trNode.appendChild(assigneeTdNode);
				trNode.appendChild(descriptionTdNode);
				trNode.appendChild(totalPointsTdNode);
				trNode.appendChild(buttonTdNode);
				
				storybody.appendChild(trNode);
			}
		});
		
		eval(JSONFile);
		//alert(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/getAllStories/334');
		

	},
	
	
	
	editStoryDialog: function() {
		var JSONFile = $.getJSON(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/getStory/' + this.id, function(data) {
			document.getElementById('edit_story_name').value = data.Name;
			document.getElementById('edit_story_id').value = data.Stories_id;
			document.getElementById('edit_assignee').value = data.Assignee;
			document.getElementById('edit_description').value = data.Description;
			document.getElementById('edit_points').value = data.Total_points;
			
			$("#edit-story-dialog-form").dialog("open");
			
		});
		
		eval(JSONFile);
		
	},
	
	deleteStoryDialog: function() {
		var JSONFile = $.getJSON(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/getStory/' + this.id, function(data) {
			
			document.getElementById('delete_story_id').value = data.Stories_id;
			var deletePnode = document.getElementById('delete_story_name');
			
			if (deletePnode.hasChildNodes() )
			{
			    while (deletePnode.childNodes.length >= 1 )
			    {
			        deletePnode.removeChild(deletePnode.firstChild );       
			    } 
			}
			
			var deleteText = document.createTextNode("Are you sure you want to delete the story \"" + data.Name + "\"?");
			deletePnode.appendChild(deleteText);
			
			document.getElementById('delete_story_id').value = data.Stories_id;
			
			$("#delete-story-dialog-form").dialog("open");
			
		});
		
		eval(JSONFile);
		
	},
	
	deleteNodes: function(parentx) {
		if (parentx.hasChildNodes() )
		{
		    while (parentx.childNodes.length >= 1 )
		    {
		        parentx.removeChild(parentx.firstChild );       
		    } 
		}
	},
	
	pointsStoryDialog: function() {
		var story_id = this.id;
		var pointsForm = document.getElementById('sprint_planner_points_form');
		
		if (pointsForm.hasChildNodes() )
		{
		    while (pointsForm.childNodes.length >= 1 )
		    {
		        pointsForm.removeChild(pointsForm.firstChild );       
		    } 
		}
		
		var fieldNode = document.createElement("fieldset");
		
		var JSONFile = $.getJSON(SITE_URL+'/widget/'+sprint_planner.widgetName + '/sprint_planner_controller/get_points/' + story_id + '/' + sprint_planner.days, function(data) {
			for(i = 1; i <= sprint_planner.days; i++) {
				//<label for="dayx">Story</label>
				//<input type="text" name="dayx" id="dayx" class="text ui-widget-content ui-corner-all" />
				var fortext = document.createTextNode('Day ' + i);
				var labelx = document.createElement("label");
				labelx.setAttribute('for', 'day'+i);
				labelx.appendChild(fortext);
				
				var inputx = document.createElement("input");
				inputx.setAttribute('type', 'text');
				inputx.setAttribute('name', 'day'+i);
				inputx.setAttribute('id', 'day'+i);
				inputx.setAttribute('maxlength', 5);
				inputx.setAttribute('size', 5);
				inputx.setAttribute('value', data[i].Points_done);
				inputx.setAttribute('class', 'text ui-widget-content ui-corner-all');
				
				var storyinput = document.createElement("input");
				storyinput.setAttribute('type', 'hidden');
				storyinput.setAttribute('id', 'sprint_planner_points_story_id');
				storyinput.setAttribute('value', story_id);
				fieldNode.appendChild(labelx);
				fieldNode.appendChild(inputx);
				fieldNode.appendChild(storyinput);
				
			}
		});
		
		eval(JSONFile);
		pointsForm.appendChild(fieldNode);
		$("#points-story-dialog-form").dialog("open");
					
	},
	
	savePoints: function() {
		var postarray = new Array();
		for(day = 1; day <= sprint_planner.days; day++) {
			//<label for="dayx">Story</label>
			//<input type="text" name="dayx" id="dayx" class="text ui-widget-content ui-corner-all" />
			var story_id = document.getElementById('sprint_planner_points_story_id').value;
			var daypoints = document.getElementById('day' + day).value;
			
			var url = SITE_URL+'/widget/'+sprint_planner.widgetName+'/sprint_planner_controller/save_points';
		
			 // prepare data to send
	        var postdata = {'story_id': story_id, 'day': day, 'daypoints': daypoints};
	                                
	        // send request
	        ajaxRequests.post(postdata, url, 'sprint_planner.savePoints_callback', 'sprint_planner.setAjaxError', true);
		}
	},
	
	savePoints_callback: function() {
		// alert("SAved!");
	},
		
	/* 
	* The following functions are common for all widgets.
    * --------------------------------------------------------------------------------------- 
    */
	
    // set content in widgets div, called from the ajax request
    setContent: function(data) {
			// The success return function, the data must be unescaped befor use.
			// This is due to ILLEGAL chars in the string.
			Desktop.setWidgetContent(unescape(data));
    },

    // set partial content in widgets div, called from the ajax request
    setPartialContent: function(data) {
			// The success return function, the data must be unescaped befor use.
			// This is due to ILLEGAL chars in the string.
			Desktop.setWidgetPartialContent(this.currentPartial, unescape(data));
			this.currentPartial = null;
    },
	
    // set error-message in widgets div, called from the ajax request
    setAjaxError: function(loadURL) {
			Desktop.show_ajax_error_in_widget(loadURL);
    },
    
    // shows a message (example in start.php)
    example_showMessage: function(message) {
			Desktop.show_message(message);    
	},
    
    // wrapper-function that easily can be used inside views from serverside    
    loadURL: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
				
        // send request
        ajaxRequests.load(url, 'sprint_planner.setContent', 'sprint_planner.setAjaxError');
    },
		
		// Loads a ajaxrequest to specific partialclass, in this case "ajax_template_partial"
	loadURLtoPartialTest: function(url) {
        // prepare url
        url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
				
        // set currentpartial to to the classname
        this.currentPartial = sprint_planner.partialContentDivClass;
        
        // send request, last parameter = true if this is a partial call. Will skip the loading image.
        ajaxRequests.load(url, 'sprint_planner.setPartialContent', 'sprint_planner.setAjaxError', true);
    },
		
    // wrapper-function that easily can be used inside views from serverside
    postURL: function(formClass, url) {
        // prepare url
        url = SITE_URL+'/widget/'+sprint_planner.widgetName+url;
				
		// catching the form data
		var postdata = $('#widget_' + Desktop.selectedWindowId ).find('.' + formClass).serialize();
				
        // send request
        ajaxRequests.post(postdata, url, 'sprint_planner.setContent', 'sprint_planner.setAjaxError');   
    }
    
};






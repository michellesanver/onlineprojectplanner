$(document).ready(function() {

	$("#tabs").tabs();
	 
    $('#tabs').bind('tabsshow', function(event, ui) {
    	sprint_planner.replot();
    });

    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#dialog:ui-dialog").dialog("destroy");

    var story = $("#story"),
        points = $("#points"),
        assignee = $("#assignee"),
        description = $("#description"),
        allFields = $([]).add(story).add(points).add(assignee).add(description),
        tips = $(".validateTips");
        
	var edit_story_name = $("#edit_story_name"),
        edit_points = $("#edit_points"),
        edit_assignee = $("#edit_assignee"),
        edit_story_id = $("#edit_story_id"),
        edit_description = $("#edit_description"),
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
                bValid = bValid && checkLength(assignee, "assignee", 6, 80);
                bValid = bValid && checkLength(description, "description", 6, 80);
                bValid = bValid && isNumber(points, "Points");

                if (bValid) {
                	sprint_planner.addStory('/sprint_planner_controller/add_story', story.val(), assignee.val(), description.val(), points.val());
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
                bValid = bValid && checkLength(edit_assignee, "assignee", 6, 80);
                bValid = bValid && checkLength(edit_description, "description", 6, 80);
                bValid = bValid && isNumber(edit_points, "Points");

                if (bValid) {

                    sprint_planner.editStory('/sprint_planner_controller/edit_story', edit_story_id.val(), edit_story_name.val(), edit_assignee.val(), edit_description.val(), edit_points.val());
                    
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

                    sprint_planner.deleteStory('/sprint_planner_controller/delete_story', document.getElementById('delete_story_id').value);
                    
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

                    sprint_planner.savePoints();
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
	
	//Render stories
	sprint_planner.renderStories();

});

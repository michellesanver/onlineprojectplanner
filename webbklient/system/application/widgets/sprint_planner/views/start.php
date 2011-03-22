<!-- END: load jqplot -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
    <title></title>
</head>

<body>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Product Backlog</a></li>

            <li><a href="#tabs-2">Sprints</a></li>
        </ul>

        <div id="tabs-1">
                    <div id="story-dialog-form" title="Add new story">
                        <p class="validateTips">All form fields are required.</p>

                        <form method="post" action="" onsubmit="return false" class="story_form">
                            <fieldset>
                                <label for="story">Story</label> 
                                <input 
                                	type="text" 
                                	name="story" 
                                	id="story" 
                                	class="text ui-widget-content ui-corner-all"> 									
                                <label for="assignee">Assignee</label> 
                                <select name="assignee" id="assignee" class="text ui-widget-content ui-corner-all">
                                	<?php foreach($members as $member): ?>
		                        		<option value="<?php echo $member['Firstname']; ?>">
		                        			<?php echo $member['Firstname']; ?>
		                        		</option>
		                        	<?php endforeach; ?>
                                </select>
                               
                                <label for="description">Description</label> 
                                <input 
                                	type="text" 
                                	name="description" 
                                	id="description" 
                                	value="" 
                                	class="text ui-widget-content ui-corner-all"> 
                                <label for="points">Total points</label> 
                                <input 
                                	type="text" 
                                	name="points" 
                                	id="points" 
                                	value="" 
                                	class="text ui-widget-content ui-corner-all">
                            </fieldset>
                        </form>
                    </div>

                    <div id="edit-story-dialog-form" title="Edit story">
                        <p class="validateTips">All form fields are required.</p>

                        <form method="post" action="" onsubmit="return false" class="story_form">
                            <fieldset>
                                <input id="edit_story_id" type="hidden" name="story_id" value=""> 
                                
                                <label for="story">Story</label> 
                                <input 
                                	type="text" 
                                	name="story" 
                                	id="edit_story_name" 
                                	class="text ui-widget-content ui-corner-all"> 
                                	
                                <label for="assignee">Assignee</label> 
                                <select name="assignee" id="edit_assignee" class="text ui-widget-content ui-corner-all">
                                	<?php foreach($members as $member): ?>
		                        		<option value="<?php echo $member['Firstname']; ?>">
		                        			<?php echo $member['Firstname']; ?>
		                        		</option>
		                        	<?php endforeach; ?>
                                </select>
                                	 
                                <label for="description">Description</label> 
                                <input 
                                	type="text" 
                                	name="description" 
                                	id="edit_description" 
                                	value="" 
                                	class="text ui-widget-content ui-corner-all"> 
                                	
                                <label for="points">Total points</label> 
                                <input 
                                	type="text" 
                                	name="points" 
                                	id="edit_points" 
                                	value="" 
                                	class="text ui-widget-content ui-corner-all">
                            </fieldset>
                        </form>
                    </div>

                    <div id="delete-story-dialog-form" title="Delete story">
                        <p id="delete_story_name">Are you sure you want to delete this story?</p>
                        <input id="delete_story_id" type="hidden" name="delete_id" value="">
                    </div>
                    
                    
                    <div id="delete-sprint-dialog-form" title="Delete sprint">
                        <p id="delete_sprint_name">Are you sure you want to delete this sprint? It can not be undone!</p>
                    </div>
                    
                    <div id="remove-story-from-sprint-form" title="Remove from sprint">
                        <p id="delete_sprint_name">Are you sure you want to remove this story? It will only be deleted from the sprint, if you want to fully delete it you have to do it from the product backlog.</p>
                    </div>

                    <div id="points-story-dialog-form" title="Edit/View points">
                        <p id="points_info"></p>

                        <form 
                        	method="post" 
                        	id="sprint_planner_points_form" 
                        	action="" 
                        	onsubmit="return false" 
                        	class="story_form" 
                        	name="sprint_planner_points_form">
                            <!-- Generated by JavaScript -->
                        </form>
                    </div>

                    <table id="stories_table" border="0" class="ui-widget">
                        <thead class="ui-widget-header">
                            <tr>
                                <th>Story</th>

                                <th>Assignee</th>

                                <th>Description</th>

                                <th>Points</th>

                                <th id="sprint_planner_create_button_container">
                                	<button id="create-story" class="add">Add</button>
                                </th>
                            </tr>
                        </thead>

                        <tbody id="story_table_content" class="ui-widget-content">
                            <!-- Content generated by JS -->
                        </tbody>
                    </table>
        </div>

        <div id="tabs-2">
        	<div id="add-sprint-dialog-form" title="Add new sprint">
                <p class="validateTips">All form fields are required.</p>

                <form method="post" action="" onsubmit="return false" class="story_form">
                    <fieldset>
                        <label for="sprintname">Name</label> 
                        <input 
                        	type="text" 
                        	name="name" 
                        	id="sprintname" 
                        	class="text ui-widget-content ui-corner-all"> 									
                        <label for="sprintdays">Days</label> 
                        <input 
                        	type="text" 
                        	name="sprintdays" 
                        	id="sprintdays" 
                        	value="" 
                        	class="text ui-widget-content ui-corner-all"> 
                    </fieldset>
                </form>
            </div>
            
            <div id="add-story-to-sprint-dialog-form" title="Add story">
                <p class="validateTips">All form fields are required.</p>

                <form method="post" action="" onsubmit="return false" class="story_form">
                    <fieldset>
                        <label for="sprintid">Sprint</label> 
                        
                        <select id="add_story_to_sprint_id" name="sprintid"class="text ui-widget-content ui-corner-all">
                        	<?php foreach($sprints as $sprint): ?>
                        		<option value="<?php echo $sprint->Sprint_id; ?>">
                        			<?php echo $sprint->Sprint_name; ?>
                        		</option>
                        	<?php endforeach; ?>
                        </select>
                        							
                        <label for="sprintstory">Story</label> 
                        
                        <select id="add_story_to_sprint_story_id" name="sprintstory" class="text ui-widget-content ui-corner-all">
                        	<?php foreach($stories as $story): ?>
                        		<option value="<?php echo $story->Stories_id; ?>">
                        			<?php echo $story->Name; ?>
                        		</option>
                        	<?php endforeach; ?>
                        </select>
                    </fieldset>
                </form>
            </div>
            <button id="add-sprint-button" class="add">Add sprint</button>
            <button id="add-story-to-sprint-button" class="add">Add or move stories</button>
            <div id="chart-form" title="Burndown chart">
            	 <div id="sprintchart" data-height="260px" data-width="600px" style="margin-top:20px; margin-left:20px;">
                </div>
            </div>
                
            <div id="sprintsaccordion">
               
            </div>
        </div>
    </div>
</body>
</html>

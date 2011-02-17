
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><b>" . $status_message . "</b></p></div>";
    }
?>
<div id="projectmember_wrapper">

	<div id="project-member-dialog-leave" title="Leave?" style="display:none;">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to leave this project?</p>
	</div>
	<div id="project-member-dialog-kick" title="Kick?" style="display:none;">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to kick this member?</p>
	</div>
	<div id="project-member-dialog-switch" title="Switch general?" style="display:none;">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to promote this member to general?</p>
	</div>
	
	<h3>Invite a new member</h3>
	<form id="<?php echo "proj_mem_".$projectID; ?>">
		<label for="email">E-mail: </label><input type="text" name="email" value="" id="email" class="required email" />*<br/>
		<label for="projectRoleID">Role in project: </label>
		<select name="projectRoleID" id="projectRoleID">

		<?php foreach($roles as $role): ?>

			<option value="<?php echo($role['Project_role_id']);?>"><?php echo($role['Role']);?></option>

		<?php endforeach; ?>

		</select>
		<br/>
		<input type="submit" value="Invite" name="invite_btn" />
	</form>
	
	<h3>Current members of this project</h3>
	<ul id="projectmember_memberlist">
			<?php foreach($members as $member): ?>
				<li>
						<?php 
							echo "<b>" . $member['Username'] . "</b>, " .$member['Firstname'] . " " . $member['Lastname'] ." (".$member['Role'].")<br /><em>" . $member['Email'] . "</em>";
							if($member['IsLoggedInUser'] != false && $isGeneral == false) {
								echo "<br /><a href=\"#\" id=\"leave_btn\">Leave</a>";
							}
							if($member['IsLoggedInUser'] == false && $isGeneral != false) { 
								if($member['Project_role_id'] == "2"){
									echo"<br /><a href=\"#\" class=\"promote_btn\" pmID=\"".$member['Project_member_id']."\">Promote to admin</a>";
								} else if($member['Project_role_id'] == "1") {
									echo"<br /><a href=\"#\" class=\"demote_btn\" pmID=\"".$member['Project_member_id']."\">Demote to member</a>";
								}
								echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"#\" class=\"kick_btn\" uID=\"".$member['User_id']."\">Kick out</a><br /><a href=\"#\"  class=\"switchgeneral_btn\" uID=\"".$member['User_id']."\">Make general</a>";
							} 
						?>
				</li>
			<?php endforeach; ?>
	</ul>
</div>
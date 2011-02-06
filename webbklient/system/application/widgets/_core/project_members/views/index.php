
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><b>" . $status_message . "</b></p></div>";
    }
?>
<div id="projectmember_wrapper">
	<h3>Invite a new member</h3>
	<form id="<?php echo "proj_mem_".$projectID; ?>" onsubmit="return projectmembers.save();">
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
								echo "<br /><a href=\"javascript:void(0);\" onclick=\"projectmembers.leave();\">Leave</a>";
							}
							if($member['IsLoggedInUser'] == false && $isGeneral != false) { 
								if($member['Project_role_id'] == "2"){
									echo"<br /><a href=\"javascript:void(0);\" onclick=\"projectmembers.promoteToAdmin(".$member['Project_member_id'].");\">Promote to admin</a>";
								} else if($member['Project_role_id'] == "1") {
									echo"<br /><a href=\"javascript:void(0);\" onclick=\"projectmembers.demoteToMember(".$member['Project_member_id'].");\">Demote to member</a>";
								}
								echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:void(0);\" onclick=\"projectmembers.kickout(".$member['User_id'].");\">Kick out</a><br /><a href=\"javascript:void(0);\" onclick=\"projectmembers.switchgeneral(".$member['User_id'].");\">Make general</a>";
							} 
						?>
				</li>
			<?php endforeach; ?>
	</ul>
</div>
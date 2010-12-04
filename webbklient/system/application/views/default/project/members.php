
<?php
    if(isset($status)) {
        echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
    }
?>
                
<h1 class="blackheader">Project members in "<?php echo (isset($title)) ? $title : ""; ?>"</h1>

<div id="projectmemberlisting">

    <div id="projectmemberinvitebox">

        <h3>Invite a new member</h3>

        <form action="<?php echo site_url('project/members/'.$projectID.''); ?>" method="POST">
            <label for="email">E-mail: </label><input type="text" name="email" value="" />*<br/>
            <label>&nbsp;</label><input type="submit" value="Invite" name="invite_btn" />
        </form>

    </div>

    <div id="projectmemberslistbox">

    <?php foreach($members as $member): ?>

        <div class="projectmemberbox">

            <h3><?php echo($member['Username'])." (".$member['Role'].")"; ?></h3>
            <p>Name: <?php echo($member['Firstname']);?></p>
            <p>Surname: <?php echo($member['Lastname']);?></p>
            <p>E-mail: <?php echo($member['Email']);?></p>

        </div>

    <?php endforeach; ?>

    </div>

</div>
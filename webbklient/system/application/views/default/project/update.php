
	
            <div id="update_box">
                <?php
                    if(isset($status)) {
                        echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                    }
                ?>

                <?php if(isset($title)) { ?>
                    <p><b><?php echo $title; ?></b></p>
                    <form action="<?php echo site_url('project_controller/update/'.$projectID.''); ?>" method="POST">
                            <input type="hidden" name="projectID" value="<?php echo (isset($projectID)) ? $projectID : ""; ?>" />
                            <p><label for="description">Description: </label><textarea rows="2" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*</p>
                            <p><input type="submit" value="Update" name="update_btn" /></p>
                    </form>
                <?php } else { ?>
                    <p><b>No project was found...</b></p>
                <?php } ?>
            </div>

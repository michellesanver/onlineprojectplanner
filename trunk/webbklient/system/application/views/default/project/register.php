
	
            <div id="register_box">
                <?php
                    if(isset($status)) {
                            echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                    }
                ?>
                <form action="<?php echo site_url('project_controller/register'); ?>" method="POST">
                        <p><label for="title">Title: </label><input type="text" name="title" value="<?php echo (isset($title)) ? $title : ""; ?>" />*</p>
                        <p><label for="description">Description: </label><textarea rows="2" cols="20" name="description"><?php echo (isset($description)) ? $description : ""; ?></textarea>*</p>
                        <p><input type="submit" value="Register" name="register_btn" /></p>
                </form>
            </div>

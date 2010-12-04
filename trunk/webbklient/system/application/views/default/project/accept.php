
	
            
                <?php
                    if(isset($status)) {
                            echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                    }
                ?>
                <form action="<?php echo site_url('project/accept'); ?>" method="POST">
                        <label for="code">Invite Code: </label><input type="text" name="code" value="<?php echo (isset($code)) ? $code : ""; ?>" />*<br/>
                        <label>&nbsp;</label><input type="submit" value="Accept" name="accept_btn" /><br/>
                </form>



	
            
                <?php
                    if(isset($status)) {
                        echo "<div class='" . $status . "'><b>" . $status_message . "</b>" . $this->validation->error_string . "<p>" . validation_errors() . "</p></div>";
                    }
                ?>

                <?php if(isset($title)) { ?>
                    
                <?php } else { ?>
                    <p><b>No project was found...</b></p>
                <?php } ?>


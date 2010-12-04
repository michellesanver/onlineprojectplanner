<?php
    if(isset($status)) {
        echo("<div class='" . 
        	$status . 
        	"'><b>" . 
        	$status_message . 
        	"</b>" . 
        	$this->validation->error_string . 
        	"<p>" . 
        	validation_errors() . 
        	"</p>
        	<p><a href=\"" . site_url('project/index') . "\">Go back to projectlisting.</a></p>
        	
        	
        	</div>");
    }
?>


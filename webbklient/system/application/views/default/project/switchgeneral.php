<?php
    if(isset($status)) {
        echo("<div class='" . 
        	$status . 
        	"'><h3>" .
        	$status_message . 
        	"</h3>" .
        	$this->validation->error_string . 
        	"<p>" . 
        	validation_errors() . 
        	"</p>
        	<p><a href=\"" . site_url('project/members/'.$projectID) . "\">Go back to project members.</a></p>
        	
        	
        	</div>");
    }
?>


<?php 
	if(!isset($Pads_Id)){
		echo '<p><input id="new_name" value="New document name!" type="text" />(cannot be changed later)</p><textarea id="pad"></textarea><p><input id="save_btn" value="save" type="button" />You can\'t coop-type before you have saved the document!</p>';
	} else {
		echo '<h4>'.$Name.'</h4><textarea id="pad" padId="'.$Pads_Id.'">'.$Text.'</textarea><p><input id="save_btn" value="save" type="button" />Created: '.$Created.' | Last edit: '.$Last_Edit.'</p>';
	}
?>

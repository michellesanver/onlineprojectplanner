<h5>Padlist</h5><ul>
	<?php 
		echo "<li class='pad-list-pad'><a href='#' padId='new'>New document</a></li>";
		foreach($pads as $pad){
			echo "<li class='pad-list-pad'><div class='delete' padId='".$pad['Pads_Id']."'></div><a href='#' padId='".$pad['Pads_Id']."'>". $pad['Name'] ."</a></li>";
		}
	?>
</ul>
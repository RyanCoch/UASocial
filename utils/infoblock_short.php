<?php
		$r = 0;
		echo "<a href=\"javascript:changeMenuView();\"><img src=\"/images/alt/star_icon.png\" class=\"icon\"><span>New on UASocial</span></a><br />";
		while($regrow[$r]['uid'] != 0){
			echo "<a href=\"/profile.php?id=".$regrow[$r]['uid']."\">";
			echo "<img style=\"width:30px;height:30px;\" align=\"left\" src=\"/pictures/".$regrow[$r]['picture']."30.jpg\"> ".$regrow[$r]['name']."</a>";
			echo "<br/>\r\n";
			$r++;
		}
		
		//unset($regrow);
?>
<?php
		$r = 0;

		echo "<a href=\"javascript:changeMenuView();\"><img src=\"images/".(($css==1)?"minimal/":"").(($css=='2')?"alt/":"")."star_icon.png\" class=\"icon\"><span>New on UASocial</span></a><br />";
		while($regrow[$r]['uid'] != 0){
			echo "<a href=\"profile.php?id=".$regrow[$r]['uid']."\">";
			echo "<img style=\"width:30px;height:30px;\" align=\"left\" src=\"pictures/".$regrow[$r]['picture']."30.jpg\"> ".$regrow[$r]['name']."</a>";
			echo "<br/>\r\n";
			$r++;
		}
		
		$r = 0;

		echo "<a href=\"javascript:changeMenuView();\"><img src=\"images/".(($css==1)?"minimal/":"").(($css=='2')?"alt/":"")."star_icon.png\" class=\"icon\"><span>New pictures</span></a><br />";
		while($lastpic[$r]['uid'] != 0){
			echo "<a href=\"pictures.php?id=".$lastpic[$r]['uid']."&display=".$lastpic[$r]['pic_id']."\">";
			echo "<img class=\"pic\" style=\"width:42px;height:30px;\" align=\"left\" src=\"".$lastpic[$r]['picture']."_sml.jpg\" /></a>";
			//if ((!($r+1)%3)) echo "<br/>\r\n";
			$r++;
		}

		
?>
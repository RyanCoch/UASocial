    <div style="height:60px; margin:20px 0 10px 8px;">
	<?php
		include_once("/home/uasocial/public_html/utils/settings.php");
		include_once(inner()."utils/functions.php");
		$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
		mysql_select_db($mysql_database, $mysql_link);

		$result8 = mysql_query("SELECT COUNT(`uid`) FROM `profile` ",$mysql_link);
		$row8 = mysql_fetch_row($result8);
		$st_prof = rand(1,($row8[0]-20));
		$result8 = mysql_query("SELECT `uid`,`picture`,`name`, `dob`, `dob_flag` FROM `profile` WHERE (`uid` >= $st_prof) AND (`picture` NOT LIKE '0/%' AND `picture` > '' ) ORDER BY RAND() LIMIT 0,8 ",$mysql_link);
		while ($row8 = mysql_fetch_array($result8)){
			$persons_age = calculate_dob($row8['dob']);
			echo "<a href=\"/profile.php?id=".$row8['uid']."\">";
			echo "<img src=\"/pictures/".$row8['picture']."60.jpg\" border=\"0px;\" style=\"margin-right:6px;\" title=\"".$row8['name'];
			if ($row8['dob_flag']) {
				echo ", ".$persons_age;
			}			
			echo "\">";
			echo "</a>";			
		}	
	?>	
    </div> <!-- bar with 8 random users -->

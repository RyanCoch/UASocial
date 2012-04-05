<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=chat.php">';
	exit;
}
	include_once("utils/settings.php");
	if (!$mysql_link) {
		$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
		mysql_select_db($mysql_database, $mysql_link);
	}	
	
	$_SESSION['chat_type'] = '1';
	$uid = $_SESSION['uid'];
	$online = $_SESSION['online']; // online expires at $online
	if (time()>$online) {
		//expired. update time in database.
		$z = addminutes(time(),1);
		$uid = $_SESSION['uid'];
		$result = mysql_query("UPDATE `online` SET `time` = '$z', `type` = '1'  WHERE uid='$uid'") or die(mysql_error());
		$_SESSION['online'] = $z;
	}
	
	
	$result = mysql_query("SELECT online.time, profile.name, profile.chat_color, profile.uid FROM online, profile WHERE online.type='1' AND online.uid = profile.uid ") or die(mysql_error());
	
	echo "<div id=\"chatonline\">\r\n<h2>Online</h2>";	
	while ($row = mysql_fetch_array($result)) {
		if ((time()-60)<=$row['time']) {
			echo "<span class=\"online\">&nbsp;</span>";
			echo "<a class=\"sex".$row['chat_color']."\" title=\"View user's profile\"  href=\"profile.php?id=".$row['uid']."\">";
			echo $row['name'];
			echo "</a>";
			
			if ($uid != $row['uid']) {
				echo "<a class=\"private_chat\" href=\"javascript:pchat(".$row['uid'].");\"><img src=\"images/newchat_icon.png\" title=\"Start private chat session\" class=\"icon_close\">";
				echo "</a>";
			}
			echo "<br />\r\n";	
			if ($row['uid']==$uid) $_SESSION['chat_color'] = $row['chat_color'];				
		}	
	}
	echo "</div><br />\r\n";
	
?>
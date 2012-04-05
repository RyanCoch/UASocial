<?php
$a = session_id();
if(empty($a)) session_start();
$wid = $_GET['wid'];					// post id
$imadmin = $_GET['admin'];
include_once('../utils/settings.php');

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

$result = mysql_query("SELECT wall_com.*, profile.name, profile.picture FROM `wall_com`, `profile` WHERE `wid` = '$wid' AND profile.uid = wall_com.uid ORDER BY `cid` ASC", $mysql_link );
while ($row = mysql_fetch_array($result)) {
	echo "<div id=\"walCom".$row['cid']."\" class=\"walCom\">";
	echo "<img src=\"/pictures/".$row['picture']."30.jpg\" style=\"margin:0 10px 5px 0;\"/>";

	$time_online = 0;
	$last_time = time() - $row['datetime'] - 30;
	if ($last_time <= 0) $time_online = "few seconds ago";
	if (!$time_online && $last_time<80) $time_online="minute ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && $last_time<80) $time_online = "about an hour ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "yesterday";
	if (!$time_online) $last_time = round($last_time/24);
	if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online) $time_online = date("h:i a, M-j",$rowpp['timestamp']);


	echo "<span class=\"wc_time\">";
	echo  $time_online ;
	if ($imadmin){
		echo "<br /><a class=\"delPost\" href=\"javascript:deletecomment(".$row['cid'].");\">";
		echo "delete";
		echo "</a>\r\n";
	}
	echo "</span>";	

	echo "<span class=\"wc_name\">";
	echo "<a href=\"/profile.php?id=".$row['uid']."\">".$row['name']."</a></span><br />";

	echo "<span class=\"wc_comment\">";
	echo stripslashes($row['comment']);
	echo "</span>";	

	echo "</div>";
}


mysql_close($mysql_link);



?>
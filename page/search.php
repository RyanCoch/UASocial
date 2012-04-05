<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}

$search = $_GET['search'];

if (!$search) exit;

include_once("/home/uasocial/public_html/utils/settings.php");

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

$result = mysql_query("SELECT `name`, `uid` FROM `profile` WHERE `name` LIKE '%$search%' LIMIT 10 ", $mysql_link);	//get 50 records from wall table
while($row = mysql_fetch_array($result)) {
	echo "<br />\r\n";
	echo "<a target=\"_blank\" style=\"display:inline-block;width:250px;\" href=\"/profile.php?id=".$row['uid']."\">";
	echo $row['name'];
	echo "</a>";
	echo "<a id=\"makead".$row['uid']."\" href=\"javascript:addasadmin(".$row['uid'].");\">make admin</a>";
}

mysql_close($mysql_link);
?>
<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}

include_once("/home/uasocial/public_html/utils/settings.php");

//$ilook4 = $_SESSION['looking_for'];	// 1 - boys 			2 - girls 			3 - anyone
if ($ilook4=='1') $sx = "AND sex = '1'";
	elseif ($ilook4=='2') $sx = "AND sex = '2'";
	else $sx='';

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);


$result = mysql_query("SELECT count(uid) FROM profile ", $mysql_link);
$row = mysql_fetch_row($result);
$magic = rand(1,$row[0]);

$result = mysql_query("SELECT uid FROM profile WHERE (picture NOT LIKE '0/%' AND picture > '' ) ".$sx." AND uid >= '$magic' LIMIT 1", $mysql_link);
$row = mysql_fetch_array($result);
$rand = $row['uid'];

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/profile.php?id='.$rand.'">';

//FROM table WHERE num_value >= RAND() * (SELECT MAX(num_value) FROM table) LIMIT 1

?>
<?php

$myid = $_POST['myid'];
$id = $_POST['id'];
$type = $_POST['type'];	// 0 - user, 1 - page

include_once("settings.php");

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

$delete = mysql_query("DELETE FROM `friends` WHERE `user1` = '$myid' AND `user2` = '$id' AND `type` = '$type' ");

mysql_close($mysql_link);


?>
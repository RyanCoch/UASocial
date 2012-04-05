<?php

session_start();

$window = $_POST['win'];
$stat = $_POST['stat'];
$id = $_POST['id'];
$uid = $_SESSION['uid'];
$cancel = $_POST['cancel'];
$confirm = $_POST['confirm'];

if ($cancel) {
	include_once("settings.php");
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$result = mysql_query("UPDATE `pchat` SET `status` = '0' WHERE `sid` = '$id' ", $mysql_link );	
	exit;
}
if (!$cancel && !$confirm && $stat=='0') {
	include_once("settings.php");
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$result = mysql_query("UPDATE `pchat` SET `status` = '0' WHERE (`user1` = '$id' AND `user2` = '$uid') OR (`user2` = '$id' AND `user1` = '$uid') ", $mysql_link );	
}
if ($confirm) {
	include_once("settings.php");
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$result = mysql_query("UPDATE `pchat` SET `status` = '2' WHERE `sid` = '$id' ", $mysql_link );	
	exit;
}


if ($_SESSION['pchat_a'][$window] == '0' || !isset($_SESSION['pchat_a'][$window])) $_SESSION['pchat_a'][$window] = $id;
$_SESSION['pchat_b'][$window] = $stat;
if ($stat == '0') $_SESSION['pchat_a'][$window] = '0';

?>
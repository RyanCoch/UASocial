<?php
$a = session_id();
if(empty($a)) session_start();

$id = $_POST['id'];				// page id
$wid = $_POST['wid'];
$official = $_POST['official']; // means POST FROM PAGE
$token = $_POST['token'];

include_once('../utils/functions.php');

//SECURITY CHECK
if (encode($_SESSION['uid'])!=$_POST['token'])
	exit;

include_once('../utils/settings.php');

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

if ($official)
	$author = 'p'.$id;
	else
	$author = 'u'.$id;

$result = mysql_query("DELETE FROM `wall` WHERE (`author`='$author' AND `wid`='$wid') ", $mysql_link );

mysql_close($mysql_link);

?>
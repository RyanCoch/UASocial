<?php
$a = session_id();
if(empty($a)) session_start();

$operation = $_POST['operation'];		// 1 = add admin, 2 = delete admin
$pid = $_POST['pid'];
$myid = $_POST['myid'];		// admin ID to add or to delete
$uid = $_SESSION['uid'];

$insert = ",".$myid;

include('/home/uasocial/public_html/utils/settings.php');
include(inner().'utils/functions.php');

//SECURITY CHECK
if (encode($_SESSION['uid'])!=$_POST['token'])
	exit;

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

$result = mysql_query("SELECT `admins` FROM `page` WHERE `pid` = '$pid' ", $mysql_link);
if (!$result) 
	exit;
$rowa = mysql_fetch_array($result);
if (!isAdmin($rowa['admins'],$uid)) 	// check if i'm admin of that page
	exit;

if (!$operation || $operation=='1') {	// add admin
	$insert = $rowa['admins'].$insert;
}
if ($operation=='2') {					// remove admin
	$insert = "";
	$alladmins = explode(',',$rowa['admins']);
	foreach($alladmins as $as)
		if ($as != $myid) {
			if ($insert) $insert.=',';
			$insert.=$as;
		}	
}

$result = mysql_query("UPDATE `page` SET `admins` = '$insert' WHERE pid='$pid'", $mysql_link);

mysql_close($mysql_link);

?>
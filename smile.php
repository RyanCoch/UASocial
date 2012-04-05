<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

	include_once("utils/settings.php");
date_default_timezone_set("America/New_York");
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	$to = $_GET['to'];
	
	$subj = "Smile :-)";
	$standard = "$name just sent you a smile :-)<br>";
	$standard .= "You can check his profile: ";
	$standard .= "<a href=\"/profile.php?id=".$uid."\">Link</a>";
	$today = date("Y-m-d H:i:s");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$query="INSERT INTO message (`mid`, `from_uid`, `to_uid`, `didread`, `subject`, `text`, `type`, `disp_s`, `disp_r`, `datetime`) VALUES ('0','$uid','$to','0', '$subj', '$standard','2','1','1','$today') ";
 	$result = mysql_query($query, $mysql_link);
	mysql_close($mysql_link);
	if (!$result) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?id='.$to.'&smile_sent=0"> ';
		exit;
	}

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=profile.php?id='.$to.'&smile_sent=1"> ';
?>
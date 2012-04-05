<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
	include_once("settings.php");
	
	$uid = $_SESSION['uid'];
	$to = $_POST['to'];

	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	//Chech if session is already exists
	
	$flag = false;

	$result = mysql_query("SELECT COUNT(sid) FROM `pchat` WHERE `user1` = '$uid' AND `user2` = '$to' ", $mysql_link );
	$r = mysql_fetch_row($result); 
	$total_records = $r[0];
	if ($total_records){
			$result = mysql_query("UPDATE `pchat` SET `status` = '1' WHERE `user1` = '$uid' AND `user2` = '$to' ", $mysql_link );
			$flag = true;	
	}

	if (!$flag) { // chech the opposite session
		$result = mysql_query("SELECT COUNT(sid) FROM `pchat` WHERE `user2` = '$uid' AND `user1` = '$to'", $mysql_link);
		$r = mysql_fetch_row($result); 
		$total_records = $r[0];
		if ($total_records){
				$result = mysql_query("UPDATE `pchat` SET `status` = '1', `user2` = '$to', `user1` = '$uid' WHERE `user2` = '$uid' AND `user1` = '$to'", $mysql_link);
				$flag = true;	
		} else { // create the session
			$logfile = "log".genRandomString().".php";
			$result = mysql_query("INSERT INTO `pchat`(`sid`, `user1`, `user2`, `status`, `logfile`) VALUES  ('0', '$uid', '$to', '1', '$logfile') ", $mysql_link);
			$directory = "pchatlogs/";
			$handle = fopen($directory.$logfile, "w");
			$fclose($handle);
		}
	}

?>
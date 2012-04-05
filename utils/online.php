<?php
$a = session_id();
if(empty($a)) session_start();
//online?

	$online = $_SESSION['online']; // online expires at $online
	if (time()>$online) {
		//expired. update time in database.
		$z = addminutes(time(),3);
		
		if ($_SESSION['chat_type']=='0' || !isset($_SESSION['chat_type']) || ($_SESSION['chat_type']=='1' && time()>($online+200))) {
			$result = mysql_query("UPDATE `online` SET `time` = '$z', `type` = '0' WHERE uid='$uid'") or die(mysql_error());
			$_SESSION['online'] = $z;
			$_SESSION['chat_type'] = '0';
			unset($result,$z,$online);
		}
	}
	
?>
<?php

// returns "user online" if user is online

	$online = "<span class=\"offline\">&nbsp;</span>";
	$result = mysql_query("SELECT `time`, `type` FROM `online` WHERE `uid`='$id' OR `uid`='$to'") or die(mysql_error());
	if (!$result) {
		//WHATAFUCK
	} else {
		$rx = mysql_fetch_array($result);
		$_SESSION['chat_type'] = $rx['type'];
		$user_time = $rx['time'];
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			}
		unset($result, $rx, $user_time);
	}
?>
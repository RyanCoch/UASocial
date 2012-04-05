<?php
	
	$myid = $_POST['myid'];
	$id = $_POST['id'];
	$type = $_POST['type'];
	
	 //Add to friends table
	 
	 include_once("settings.php");

		$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
		mysql_select_db($mysql_database, $mysql_link);

		$get_contact = mysql_query("INSERT INTO `friends`(`fid`, `user1`, `user2`, `type`) VALUES ('0', '$myid', '$id', '$type')", $mysql_link);	//is he in your contacts

		mysql_close($mysql_link);


?>
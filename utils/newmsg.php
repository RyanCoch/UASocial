<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=chat.php">';
	exit;
}
	include_once("settings.php");
	if (!$mysql_link) {
		$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
		mysql_select_db($mysql_database, $mysql_link);
	}	
	
$get_messages = mysql_query("SELECT COUNT(mid) FROM message WHERE to_uid='$uid' AND didread='0' ", $mysql_link);
if (!$get_messages) {
	//disregard
 } else {
	$rz = mysql_fetch_row($get_messages); 
	$num_messages = $rz[0];
 }

$chat = ($_SESSION['display_new_here'] == '1');
$menu_type = $_SESSION['menu_type'];

	if ($num_messages>0){
			
			echo "<ul class=\"leftmenu_sm\" id=\"small_menu_admin\"".((!$menu_type && !$chat)?"style=\"display:none;\"":"").">";
			echo "<li class=\"orange wide\">";
			echo "<a href=\"messages.php\"><img src=\"images/newmessage_icon.png\" class=\"icon\">";
			echo $num_messages." new message".(($num_messages%10!=1)?"s":"")."!";
			echo "</a></li>";
			echo "</ul>";

			echo "\r\n<ul class=\"leftmenu\" id=\"big_message_menu\"".(($menu_type || $chat)?"style=\"display:none;\"":"")."><li class=\"orange\">";
			echo "<a href=\"messages.php\"><img src=\"images/newmessage_icon.png\" class=\"icon\">";
			echo $num_messages." new message".(($num_messages%10!=1)?"s":"")."!";
			echo "</a>";
			echo "</li></ul>\r\n";		
	}

if ($chat) {
	// get info from "pchat" table
	// if status == 1 display the notification
	
	//////////////////////////////////
	if ($_SESSION['uid'] == '1' || $_SESSION['uid'] == '2') {
		$pchat_from = "Robert Smith";
	//////////////////////////////////
	
	echo "<ul class=\"leftmenu_pchat\" id=\"small_menu_chat_notification\">";
	
	//// may be more than ONE LI
	
	echo "<li class=\"pchat\">";
	echo "<img src=\"images/newchat_icon.png\" class=\"icon\">";
	echo "<small>Private chat with</small><br/><a href=\"profile\" style=\"display:block;width:125px;margin:0 0 0 25px;padding:0px;\">";
	echo $pchat_from;
	echo "</a><p class=\"no_margins\" style=\"text-align:right;\">";
	echo "<a href=\"javascript:void();\" class=\"confirm\"><img src=\"images/newchat_confirm_icon.png\" class=\"icon_close\"> confirm</a> <a href=\"javascript:void();\" class=\"ignore\"><img src=\"images/newchat_ignore_icon.png\" class=\"icon_close\"> ignore</a>";
	echo "</p>";
	echo "</li>";
	
	
	echo "</ul>";
	
	
	} ///////////////////////////////
}

?>
<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}
if (!isset($urladdress)) include_once("/home/uasocial/public_html/utils/settings.php");
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
if (!$chat) $chat = $_GET['chat'];
$menu_type = $_SESSION['menu_type'];

	if ($num_messages>0){
		?>
			<script type="text/javascript">
document.title = "UASocial ("+<?php echo $num_messages; ?> +")";
</script>
<?php
			echo "<ul class=\"leftmenu_sm\" id=\"small_menu_admin\"".((!$menu_type && !$chat)?"style=\"display:none;\"":"").">";
			echo "<li class=\"orange wide\">";
			echo "<a href=\"/messages.php\"><img src=\"/images/alt/newmessage_icon.png\" class=\"icon\">";
			echo $num_messages." new message".(($num_messages%10!=1)?"s":"")."!";
			echo "</a></li>";
			echo "</ul>";

			echo "\r\n<ul class=\"leftmenu\" id=\"big_message_menu\"".(($menu_type || $chat)?"style=\"display:none;\"":"")."><li class=\"orange\">";
			echo "<a href=\"/messages.php\"><img src=\"/images/alt/newmessage_icon.png\" class=\"icon\">";
			echo $num_messages." new message".(($num_messages%10!=1)?"s":"")."!";
			echo "</a>";
			echo "</li></ul>\r\n";		
	}

//if ($chat) {
	// get info from "pchat" table
	// if status == 1 display the notification

	$get_pchat = mysql_query("SELECT COUNT(sid) FROM pchat WHERE user2='$uid' AND status='1' ", $mysql_link);
	if (!$get_pchat) {
		//disregard
	 } else {
		$rz = mysql_fetch_row($get_pchat); 
		$num_pchat = $rz[0];
	 }

	if ($num_pchat > 0) {
		$get_pchat = mysql_query("SELECT pchat.*, profile.name FROM pchat, profile WHERE pchat.user2='$uid' AND pchat.status='1' AND profile.uid=pchat.user1 ", $mysql_link);
	
		//// may be more than ONE LI
		while($row = mysql_fetch_array($get_pchat)) {
				
            echo "<script type=\"text/javascript\">\r\n $(document).ready(function(){pchat_confirm(".$row['user1'].", ".$row['sid'].");});\r\n</script>\r\n";
		
		}
	
	}
//}

?>
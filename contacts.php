<?php
/* Messages.php Created and Monitored By Ryan Cochrane/Mike Gordo */
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
	include_once("utils/settings.php");
	
	$uid = $_SESSION['uid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UASocial contacts</title>

<?php
if (isset($_SESSION['css']))
	$css = $_SESSION['css'];
	else
	$css = '2';
	
if ($css == '0'):
?>
<link href="css/uadn.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '1') :
?>
<link href="css/uadn.css" rel="stylesheet" type="text/css" />
<link href="images/minimal/uadn.css" rel="stylesheet" type="text/css" />
<link href="images/minimal/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '2') :
?>
<link href="css/uadn_alt.css" rel="stylesheet" type="text/css" />
<link href="css/button_alt.css" rel="stylesheet" type="text/css" />
<?php endif;
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
	function pchat(to_id) {
		parent.pchat(to_id);
	}
</script>

<script type="text/javascript">
	function delContact(to_id, type){
	$.post("utils/delcontact.php", {myid: <?php echo $uid; ?>, id: to_id, type: type});	
	loadContacts();
	}
</script>
<script type="text/javascript">
function loadContacts() {		
		$.ajax({
			url: "contacts.php",
			cache: false,
			success: function(html){	
			$("#contacts").html(html);	
		  	},
		});
	}
</script>
</head>

<body>
<div id="contacts">
    <?php
    $mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	$result = mysql_query("SELECT profile.name, profile.picture, online.time, friends.user2 FROM profile, online, friends WHERE (profile.uid = friends.user2 AND friends.user1='$uid' AND profile.uid = online.uid AND friends.type = '0') ORDER BY profile.name ASC", $mysql_link);
	echo "<div id=\"contact_list_\">";
	$fid = 0;
    while ($row = mysql_fetch_array($result)) {
		$fid++;
		$online = "<span class=\"offline\">&nbsp;</span>";
		$user_time = $row['time'];
		$zon = 0;
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			$zon = 1;
			}
		
		echo "<a target=\"_parent\" href=\"profile.php?id=".$row['user2']."\">";
		echo "<img align=\"left\" src=\"pictures/".$row['picture']."30.jpg\"></a>";
		echo "<p><a target=\"_parent\" class=\"name\" href=\"profile.php?id=".$row['user2']."\">";
		echo "<strong>".$row['name']."</strong>\r\n";
		echo "</a>".$online;
		echo "<a id=\"delContact\" href=\"javascript: delContact(".$row['user2'].",0);\"><img src=\"images/transparent.png\" width=\"10\" height=\"10\" class=\"smico\" title=\"Delete from contacts\"/></a>";
		echo "<br>";
		echo "<img src=\"images/alt/cicon1.png\" class=\"smico\"><a target=\"_parent\" href=\"messages.php?to=".$row['user2']."\">message</a> ";
		if ($zon) {
			echo "<img src=\"images/alt/cicon2.png\" class=\"smico\"><a target=\"_parent\" href=\"javascript:pchat(".$row['user2'].");\">chat</a>";
		}
		echo "</p>\r\n";
	 }
	echo "</div>\r\n<hr>\r\n";
	
	// display pages now
	
	$result = mysql_query("SELECT page.name, page.picture, friends.user2 FROM page, friends WHERE (page.pid = friends.user2 AND friends.user1='$uid' AND friends.type = '1') ORDER BY page.name ASC", $mysql_link);
	echo "<div id=\"contact_list_\">";
    while ($row = mysql_fetch_array($result)) {
		$fid++;
		echo "<a target=\"_parent\" href=\"/page/?id=".$row['user2']."\">";
		echo "<img align=\"left\" src=\"/page/pictures/".$row['picture']."30.jpg\"></a>";
		echo "<p><a target=\"_parent\" class=\"name\" href=\"/page/?id=".$row['user2']."\">";
		echo "<strong>".$row['name']."</strong>\r\n";
		echo "</a>";
		echo "<a id=\"delContact\" href=\"javascript: delContact(".$row['user2'].",1);\"><img src=\"images/transparent.png\" width=\"10\" height=\"10\" class=\"smico\"/></a>";
		echo "<br>";
		echo "<br>";
		echo "</p>\r\n";
	 }
	echo "</div>\r\n";
	
	
	
	$_SESSION['num_contacts'] = $fid;
 	mysql_close($mysql_link);
    ?>
    
    
    
    
</div>
</body>
</html>
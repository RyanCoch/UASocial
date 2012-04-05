<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php">';
	exit;
}

include ('../top/header.php'); 

	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	
	//check if i'm admin
	$flag = 0;
	for ($i = 0; $i < $number_of_admins; $i++)
		if ($adminid[$i] == $uid) $flag = 1;
	
	if (!$flag) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php">';
		exit;
	}
	
	if ($flag == 1) :	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	?>
	 <div id="left" style="min-height:750px;">


<?php 
include(inner()."utils/leftmenu.php");
?>


	</div> <!-- left -->

	<div id="right" style="min-height:750px;">
	<div id="profile">
	<h2>Statistics <a href="./">admin console</a></h2>    
    <h3>Users</h3>
    
    <?php
	$query = mysql_query("SELECT COUNT(uid) FROM profile", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_users = $row[0];

	echo "<p>Total number of registered users: <strong>".$num_users."</strong><br />\r\n";

	$today = strtotime ( '-1 month' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND registered > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Registered within the last month: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 week' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND registered > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Registered within the last week: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 day' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND registered > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Registered today: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";

	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active < 1 ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Total number of inactive profiles: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";

	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND registered = last_login ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Activated their profile and never logged in again: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";
	
	$today = strtotime ( '-1 week' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND last_login > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Visitors within a week: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 day' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(uid) FROM profile WHERE active > 0 AND last_login > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Visitors today: <strong>".$num_inactive." (".floor($num_inactive/$num_users*100)."%)</strong></p>\r\n";
	
	?>

    <h3>Messages</h3>
    
    <?php
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 1", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_msg = $row[0];

	echo "<p>Total number of messages (excluding deleted): <strong>".$num_msg."</strong><br />\r\n";

	$today = strtotime ( '-1 month' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 1 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Messages sent during the last month (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 week' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 1 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Messages sent during the last week (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 day' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 1 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Messages sent today (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong></p>\r\n";
?>    
    <h3>Smiles</h3>
    
    <?php
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 2", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_msg = $row[0];

	echo "<p>Total number of smiles (excluding deleted): <strong>".$num_msg."</strong><br />\r\n";

	$today = strtotime ( '-1 month' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 2 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Smiles sent during the last month (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 week' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 2 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Smiles sent during the last week (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong><br />\r\n";

	$today = strtotime ( '-1 day' , time());
	$today = date ( 'Y-m-j G:i:s' , $today );
	$query = mysql_query("SELECT COUNT(mid) FROM message WHERE type = 2 AND datetime > '$today' ", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_inactive = $row[0];

	echo "Smiles sent today (excluding deleted) <strong>".$num_inactive." (".floor($num_inactive/$num_msg*100)."%)</strong></p>\r\n";
?>
    
    <h3>Pictures</h3>
    
    <?php
	$query = mysql_query("SELECT COUNT(pic_id) FROM picture", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_pictures = $row[0];

	echo "<p>Total number of pictures: <strong>".$num_pictures."</strong><br />\r\n";
	$float = $num_pictures/$num_users;
	if (strlen("".$float)>4) $float = substr($float,0,4);
	echo "Average number of pictures per user: <strong>".$float."</strong></p>\r\n";

?>
	
</div> <!-- profile -->
</div> <!-- right -->
<?php	
mysql_close($mysql_link);	
endif;
include ('../top/footer.php'); 
?>
<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

include ('top/header.php'); 

	//extract all the data
	$fuid  = $_GET['uid'];
	$id = $_GET['id'];
	$pid = $_POST['pid'];
	if (!$pid)
		$pid = $_GET['pid'];
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	
	$report = $_POST['report'];
	$from = $_POST['from'];
	$user = $_POST['user'];
	$text = addslashes(strip_tags($_POST['text']));
	
	$block_action = $_POST['block_action'];
	$report_action = $_POST['report_action'];

	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	if ($report == 1) :
		//DO THE MAGIC
		if ($block_action == 1 && !$pid) {
			//block the user
			$query = mysql_query("INSERT INTO block (`user1`, `user2`) VALUES ('$from','$user') ", $mysql_link);
		}
		if ($report_action == 1) {
			//report the user
			$today = date("Y-m-d H:i:s");
			$subject = "REPORT USER: id".$user;
			if ($pid) $subject = "REPORT PAGE: id".$pid;
			
			$text = $subject."<br/><br/>".$text;
		  	
			for ($i = 0; $i < $number_of_admins; $i++) {
				$query = "INSERT INTO message (mid, from_uid, to_uid, didread, subject, text, type, disp_s, datetime) VALUES  ('0','$from','$adminid[$i]','0','$subject','$text', '3', '0', '$today')";
				$result = mysql_query($query, $mysql_link);
			}
		}	

		mysql_close($mysql_link);
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php">';
		exit;
	endif;	
	
	if ($fuid != $uid) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php">';
		exit;
	}

	if (!$pid)
		$query = mysql_query("SELECT name FROM profile WHERE uid='$id' ", $mysql_link);
	else
		$query = mysql_query("SELECT name FROM page WHERE pid='$pid' ", $mysql_link);
	
	$row = mysql_fetch_array($query);
	if (!$query) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/myprofile.php">';
		exit;
	 }
?>	 
	 <div id="left" style="min-height:750px;">

<?php 
include(inner()."utils/leftmenu.php");
?>

	</div> <!-- left -->

	<div id="right" style="min-height:750px;">
	<div id="profile">
	<h2>Report the user</h2>  
<?php
   		echo "<div id=\"report\">";
		echo "<p>";
		echo "<form method=\"post\" action=\"report.php\">\r\n";
		
		if (!$pid) {
			echo "<input class=\"check\" type=\"checkbox\" name=\"block_action\" value=\"1\">Block<br/>";
			echo "<small>User won't be able to see your profile or commuticate with you.</small><br/>";
		}
		
		echo "<input class=\"check\" type=\"checkbox\" name=\"report_action\" value=\"1\">Report<br/>";
		
		if (!$pid)
			echo "<small>User will be reported to the administration.</small><br/><small>After investigation this profile may be permanently blocked.</small><br/>";		
		
		echo "<br />";
		if (!$pid)
			echo "<span class=\"error\">* please fill this form only in you're willing to report the user.</span><br />\r\n";
		echo "<br />";
		echo "<input type=\"hidden\" name=\"report\" value=\"1\">\r\n";
		echo "<input type=\"hidden\" name=\"from\" value=\"".$uid."\">\r\n";
		echo "<input type=\"hidden\" name=\"user\" value=\"".$id."\">\r\n";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$pid."\">\r\n";
		echo "<span class=\"left from\">Request from:</span><span class=\"right\">".$name."</span><br />\r\n";
		
		if (!$pid)
			echo "<span class=\"left to\">Report user:</span><span class=\"right\">".$row['name']."</span><br />\r\n";
		else
			echo "<span class=\"left to\">Report page:</span><span class=\"right\">".$row['name']."</span><br />\r\n";
			
		echo "<span class=\"right msg\">";
		echo "<textarea name=\"text\" maxlength=\"1000\" style=\"width:475px;\" rows=\"8\"></textarea>";	
		echo "</span><br /><br />\r\n";
		echo "<button class=\"bsettings\" type=\"submit\" value=\"Submit request\">Submit request</button>\r\n";
		echo "</p>";
		echo "</div>\r\n";
?>    
</div> <!-- profile -->
</div> <!-- right -->
<?php 
include ('footer.php'); 
mysql_close($mysql_link);

?>
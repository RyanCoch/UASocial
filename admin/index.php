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
	
	$action = $_GET['action'];
	$id = $_GET['id'];
	$id2 = $_GET['id2'];
	$d_nots = $_GET['d_nots'];
	$d_inact = $_GET['d_inact'];
	
	if ($action == 'a' && $id) {
		$key = rand(1601,35000);	//random number
		$query = "UPDATE profile SET active = '$key' WHERE uid = '$id'";		
	}
	if ($action == 't' && $id) {
		$query = "UPDATE profile SET active = '-1' WHERE uid = '$id'";		
	}
	if ($action == 'b' && $id) {
		$query = "UPDATE profile SET active = '-2' WHERE uid = '$id'";		
	}
	if ($action == 'u' && $id && $id2) {
		$query = "DELETE FROM `block` WHERE user1='$id' AND user2='$id2'";		
	}
	if ($action == 'z' && $id && $id2) {
		$query = "INSERT INTO `block` (`user1`, `user2`) VALUES ('$id','$id2')";		
	}
	
	if ($action) 
		$result = mysql_query($query, $mysql_link);
	?>
	 <div id="left" style="min-height:750px;">


<?php 
include(inner()."utils/leftmenu.php");
?>


	</div> <!-- left -->

	<div id="right" style="min-height:750px;">
	<div id="profile">
	<h2>Admin console <a href="statistics.php">statistics</a></h2>  
    <h3>Reports</h3>
    <p>Here are all the reports addressed to you</p>
    
    <?php
	$query = "SELECT message.*, profile.name, profile.uid FROM message, profile WHERE message.type = '3' AND message.to_uid='$uid' AND profile.uid = message.from_uid AND message.disp_r ='1' ORDER BY message.datetime DESC ";
	$result = mysql_query($query, $mysql_link);
		echo "<table class=\"grey wide\" cellpadding=\"2px\" cellspacing=\"1px\">\r\n";
		echo "<tr class=\"new\">";
		echo "<td class=\"select\">&nbsp;</td>";
		echo "<td class=\"from\">From</td>";
		echo "<td class=\"subj\">Subject</td>";
		echo "<td class=\"date\">Date</td>";
		echo "</tr>\r\n";
		while ($row = mysql_fetch_array($result)) {
			$ddate = date('M, j h:i A', strtotime($row['datetime']));
			if($row['didread'] == 0)
				echo "<tr class=\"new\">";
			  else
				echo "<tr class=\"old\">";
			echo "<td class=\"type".$row['type']."\">";//<input type=\"checkbox\" name=\"checkbox[]\" value=\"$mid\" class=\"check\"/>";
			echo "<input type=\"hidden\" name=\"value\" value=\"1\"/></td>\r\n";
			echo "<td><a href=\"../profile.php?id=".$row['from_uid']."\">".$row['name']." (".$row['from_uid'].")</a></td>";
			echo "<td><a href=\"../replymessage.php?messageid=".$row['mid']."\">".$row['subject']."</a></td>";
			echo "<td>".$ddate."</td>";
			echo "</tr>\r\n";
		} //FOR LOOP
	echo "</table>\r\n";
	?>
    <br />
    <h3>Blocked profiles</h3>
    <p>Here are all the deactivated or blocked profiles<br />
    <small><strong>0</strong> - inactive, <strong>-1</strong> - not in search, <strong>-2</strong> - permanently blocked</small></p>
    <form action="index.php" method="get"> <input type="checkbox" class="check" name="d_inact" <?php if($d_inact) echo "checked";?> value="1" />Display inactive &nbsp;&nbsp;&nbsp;
    <input type="checkbox" class="check" name="d_nots" <?php if($d_nots) echo "checked";?> value="1" />Display not in search&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Go" /></form>
    
    <?php
	$query = "SELECT name, uid, email, active, last_login, registered FROM profile WHERE active = -2 ".(($d_nots)?"OR active = -1 ":"").(($d_inact)?"OR active = 0 ":"");
	$result = mysql_query($query, $mysql_link);
		echo "<table class=\"grey wide\" cellpadding=\"2px\" cellspacing=\"1px\">\r\n";
		echo "<tr class=\"new\">";
		echo "<td class=\"select\">UId</td>";
		echo "<td class=\"from\">Name</td>";
		echo "<td class=\"active\">State</td>";
		echo "<td class=\"date\">Last login</td>";		
		echo "<td class=\"btn\">Actions</td>";
		echo "</tr>\r\n";
		while ($row = mysql_fetch_array($result)) {
			$ddate = date('M, j h:i A', strtotime($row['datetime']));
			if($row['active'] == 0)
				echo "<tr class=\"few\">";
			  elseif($row['active'] == -1)
				echo "<tr class=\"old\">";
			  elseif($row['active'] == -2)
				echo "<tr class=\"new\">";
			// uid | name | state | date | actions	
			echo "<td><a href=\"../profile.php?id=".$row['uid']."\">".$row['uid']."</a></td>\r\n";
			echo "<td title=\"".$row['email']."\">".$row['name']."</td>\r\n";
			echo "<td align=\"center\">".$row['active']."</td>\r\n";
			$ddate = date('M, j h:i A', strtotime($row['last_login']));
			echo "<td title=\"Registered on ".$row['registered']."\">".$ddate."</td>\r\n";
			echo "<td>";
			echo "<a href=\"../messages.php?to=".$row['uid']."\">msg</a>&nbsp;&nbsp;&nbsp;";
			echo "<a href=\"index.php?action=a&id=".$row['uid']."\">act</a>&nbsp;&nbsp;&nbsp;";
			echo "<a href=\"index.php?action=t&id=".$row['uid']."\">investigate</a>&nbsp;&nbsp;&nbsp;";
			echo "<a href=\"index.php?action=b&id=".$row['uid']."\">perm block</a>&nbsp;&nbsp;&nbsp;";
			echo "</td>";
			echo "</tr>\r\n";
		} //FOR LOOP
	echo "</table>\r\n";
	?>
    <form action="index.php" method="get">
    <input type="hidden" name="action" value="t"/>
    <input type="hidden" name="d_nots" value="1"/>
    Investigate user id: <input type="text" name="id" size="10" width="100px" />
    <input type="submit" value="Go" /></form>
    
    <br />
    <h3>Blocked user pairs</h3>
    <p>Here are all the blocked user pairs<br />
    <?php
	$query = "SELECT block.*, profile.name FROM block, profile WHERE block.user1 = profile.uid";
	$query2 = "SELECT block.*, profile.name FROM block, profile WHERE block.user2 = profile.uid";
	$result = mysql_query($query, $mysql_link);
	$result2 = mysql_query($query2, $mysql_link);
		echo "<table class=\"grey wide\" cellpadding=\"2px\" cellspacing=\"1px\">\r\n";
		echo "<tr class=\"new\">";
		echo "<td class=\"select\">UId</td>";
		echo "<td class=\"from\">Name</td>";
		echo "<td class=\"select\"></td>";
		echo "<td class=\"select\">UId</td>";
		echo "<td class=\"from\">Name</td>";
		echo "<td class=\"btn\">Actions</td>";
		echo "</tr>\r\n";
		while ($row = mysql_fetch_array($result)) {
			$row2 = mysql_fetch_array($result2);
			echo "<tr class=\"old\">";
			echo "<td>".$row['user1']."</td>\r\n";
			echo "<td>".$row['name']."</td>\r\n";
			echo "<td></td>\r\n";
			echo "<td>".$row['user2']."</td>\r\n";
			echo "<td>".$row2['name']."</td>\r\n";

			echo "<td>";
			echo "<a href=\"index.php?action=u&id=".$row['user1']."&id2=".$row['user2']."\">unblock</a>";
			echo "</td>";
			echo "</tr>\r\n";
		} //FOR LOOP
	echo "</table>\r\n";
	?>
    <form action="index.php" method="get">
    <input type="hidden" name="action" value="z"/>
    Block user id1: <input type="text" name="id" size="10" width="100px" />
    Block user id2: <input type="text" name="id2" size="10" width="100px" />
    <input type="submit" value="Go" /></form>
    
	
</div> <!-- profile -->
</div> <!-- right -->
<?php	
mysql_close($mysql_link);	
endif;
include ('../top/footer.php'); 
?>
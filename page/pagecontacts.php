<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
unset($a);
	
	include ('/home/uasocial/public_html/top/header.php'); 

	//extract all the data
	$id = $_GET['id'];
	$uid = $_SESSION['uid'];
	$search_page = $_GET['page'];
	$byname = $_GET['byname'];
	$admins = $_GET['admins'];
	$error = $_GET['error'];
	$search_name = trim(strip_tags($_GET['search_name']));
 	if (!$search_page) $search_page = "0";
	
	if ((!$id) || ($id == 0)) {	//id of page is not set
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
		exit;		
	}
	
	include_once(inner()."utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	include_once(inner()."utils/online.php");		// UPDATE MY ONLINE STATUS

	$rz = mysql_query("SELECT * FROM `page` WHERE `pid` = '$id'", $mysql_link);
	$rowp = mysql_fetch_array($rz);
	
	if (empty($rowp)) {
		// no  such page
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
		exit;		
	 }

	$imadmin = isAdmin($rowp['admins'],$uid);	// checks if i'm admin of this page

	if (!$imadmin) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
		exit;		
	}
		
	$pname = $rowp['name'];
	$ppicture = $rowp['picture'];
	?>
    <script type="text/javascript">
		document.title = "<?php echo $pname; ?>";
	</script>

<div id="left" style="min-height:750px;">

<h2 class="name"><?php echo $pname; ?></h2>

<?php 
echo '<p class="incontact">&nbsp;</p>';
?>

<a href="./?id=<?php echo $id; ?>">
<img src="<?php echo "pictures/".$ppicture."170.jpg"; ?>" alt="Page Picture" width="170" height="220" class="profile_picture" />
</a>

<ul class="leftmenu">
    <li><a href="/page/?id=<?php echo $id; ?>"><img src="/images/alt/profile_icon.png" class="icon">Page Profile</a></li>
    <li><a href="/page/pagepictures.php?id=<?php echo $id; ?>"><img src="/images/alt/picture_icon.png" class="icon">Pictures</a></li>
    <li class="green"><a href="/page/pagesettings.php?id=<?php echo $id; ?>"><img src="/images/alt/settings_icon.png" class="icon">Edit Page</a></li>
    <li class="green"><a href="/page/pagecontacts.php?id=<?php echo $id; ?>"><img src="/images/alt/contacts_icon.png" class="icon">Contact List</a></li>
</ul>

<?php
echo "<div id=\"new_messages\">";
include (inner()."newmsg.php");
echo "</div>\r\n";
?>


<div id="chatonline" style="width:170px; margin-top:20px;">
    <a href="/page/NewPage.php">Create a Page</a><br />
    <a href="/report.php?uid=<?php echo $uid; ?>&pid=<?php echo $id;?>">report/block
    </a>
</div>

</div> <!-- left -->


<div id="right" style="min-height:750px;">
<div id="profile">
<h2>Page Contacts</h2>

  <?php
		if ($error == "notfound") {
			echo "<p class=\"error\">Your search did not find that contact.</p>";
			echo "<p>Please try another search.</p>";
			}
	?>
<script type="text/javascript">
function addasadmin(id){
	$.post("makeadmin.php", {myid: id, pid: <?php echo $id;?>, token: '<?php echo encode($_SESSION['uid']);?>', operation: 1 });
	
	 $('#admin_link'+id).fadeOut('fast', function() {
        document.getElementById('admin_link'+id).style.display = "none";
		document.getElementById('isadmin'+id).style.display = "inline";
		$('#isadmin'+id).fadeIn('fast');
      });
}
</script>

 <form method="get" action="pagecontacts.php?byname=1" id="search_form">  
 <label for="agefr">Name</label>
 <input class="tsearch" type="text" name="search_name" size="50" value="<?php echo $search_name;?>"/><br />
 <input type="hidden" name="byname" value="1" />
 <input type="hidden" name="id" value="<?php echo $id; ?>" />
 <button type="submit" class="bsearch" value="search">Search</button>
</form>

<?php
echo "<p style=\"color:#666; font-size:1em;\">The following user's have <strong>".$pname."</strong> in their contacts.";
echo "</p>";
?>

<?php
//Display all contacts with pages algorithm
if(!$byname || !$search_name){
	$query = "SELECT COUNT(friends.user2) FROM friends WHERE friends.user2 = '$id' AND friends.type = '1'";
	$result = mysql_query($query, $mysql_link); 
	$row = mysql_fetch_row($result); 
	$total_records = $row[0];
	if($total_records == 0){
	echo "<p class=\"error\">No contacts found</p>";
	}else{
	echo "<p style=\"margin:3px 0;\"><small>".$total_records." contacts found</small></p>\r\n";
	}
	unset($pname);
if ($total_records>$messages_per_page):
	$pages = ceil($total_records/$messages_per_page);
	//display pages
	
	$ista = 0;
	$iend = $pages;
	if ($pages>9) {
		$ista = $search_page - 4;
		$iend = $search_page + 4;
		if ($ista < 0) {
			$ista = 0;
			$iend = 9;
		}
		if ($iend > $pages) {
			$iend = $pages;
			$ista = $iend - 8;
		}
	}	
		
    $bar = "<div class=\"bar2\"><p><strong>Pages: </strong>";
	if ($ista>0) $bar .= "... ";
			for ($i = $ista; $i < $iend; $i++) {
				$bar.= "<a href=\"pagecontacts.php?id=".$id."&page=$i\"".($search_page==$i?" class='active' ":"").">";
				$bar.= ($i+1);
				$bar.= "</a>\r\n";
			}
    if ($iend<$pages) $bar .= " ... ";
	$bar .= "</p></div>	\r\n";
	echo $bar;
	endif;

    $search_page = $search_page * ($messages_per_page-18);
	$result = mysql_query("SELECT profile.name, profile.uid, profile.picture, online.time, friends.user2, friends.user1 FROM profile, online,friends WHERE (profile.uid = friends.user1 AND profile.uid = online.uid AND friends.user2='$id' AND friends.type = '1') ORDER BY profile.name ASC LIMIT $search_page, $messages_per_page", $mysql_link);
	echo "<div id=\"pagecontacts\">";
	$fid = 0;
    while ($row = mysql_fetch_array($result)) {
		$fid++;
		$online = "<span class=\"offline\">&nbsp;</span>";
		$user_time = $row['time'];
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			}

		echo "<div class=\"pc\">\r\n";
		echo "<a href=\"/profile.php?id=".$row['uid']."\">";
		echo "<img align=\"left\" src=\"/pictures/".$row['picture']."30.jpg\" border=\"0\"></a>";
		echo "<p><a class=\"name\" href=\"/profile.php?id=".$row['uid']."\">";
		if($row['uid'] == $uid){
		echo 'You';
		}else{
		echo $row['name'];
		}
		echo "</a>".$online;
		echo "</p>\r\n";
		if($row['user1'] != $uid)
		echo "<a target=\"_parent\" href=\"/messages.php?to=".$row['user1']."\">Send Message</a> ";
		if(!isAdmin($rowp['admins'],$row['user1'])){
			echo "<a href=\"javascript:addasadmin(".$row['user1'].");\" class=\"admin_link\" id=\"admin_link".$row['user1']."\">Make Admin</a>";
			echo "<span class=\"isadmin\" id=\"isadmin".$row['user1']."\" style=\"display:none;\">ADMIN</span>";
		} else {
			echo "<span class=\"isadmin\"><strong>ADMIN</strong></span>";	
		}
		echo "</div>\r\n\r\n";
	 }
	echo "</div>\r\n";
	if ($total_records>$messages_per_page) echo $bar;
	}
?>
<?php
	if($byname && $search_name && !$error){
	$srch="%".$search_name."%"; 
	$query = "SELECT profile.name, profile.uid, profile.picture, online.time, friends.user2, friends.user1 FROM profile, online,friends WHERE (profile.uid = friends.user1 AND profile.uid = online.uid AND friends.user2='$id' AND friends.type = '1' AND profile.name LIKE '$srch') ORDER BY profile.name DESC LIMIT 0, 10";
		$result = mysql_query($query, $mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//cant find anybody
		 mysql_close($mysql_link);
		 echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=pagecontacts.php?error=notfound&byname=$byname&search_name=$search_name&id=".$id."\">";
		 exit;
	 }	 
	echo "<div id=\"pagecontacts\">";
	$fid = 0;
    while ($row = mysql_fetch_array($result)) {
		$fid++;
		$online = "<span class=\"offline\">&nbsp;</span>";
		$user_time = $row['time'];
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			}

		echo "<div class=\"pc\">\r\n";
		echo "<a href=\"/profile.php?id=".$row['uid']."\">";
		echo "<img align=\"left\" src=\"/pictures/".$row['picture']."30.jpg\" border=\"0\"></a>";
		echo "<p><a class=\"name\" href=\"/profile.php?id=".$row['uid']."\">";
		if($row['uid'] == $uid){
		echo 'You';
		}else{
		echo $row['name'];
		}
		echo "</a>".$online;
		echo "</p>\r\n";
		if($row['user1'] != $uid)
		echo "<a target=\"_parent\" href=\"/messages.php?to=".$row['user1']."\">Send Message</a> ";
		if(!isAdmin($rowp['admins'],$row['user1'])){
			echo "<a href=\"javascript:addasadmin(".$row['user1'].");\" class=\"admin_link\" id=\"admin_link".$row['user1']."\">Make Admin</a>";
			echo "<span class=\"isadmin\" id=\"isadmin".$row['user1']."\" style=\"display:none;\">ADMIN</span>";
		} else {
			echo "<span class=\"isadmin\"><strong>ADMIN</strong></span>";	
		}
		echo "</div>\r\n\r\n";
	 }
	echo "</div>\r\n";
	 
	}
?>
</div> <!-- profile -->
</div> <!-- right -->

<?php 
include (inner().'top/footer.php'); 
mysql_close($mysql_link);
?>
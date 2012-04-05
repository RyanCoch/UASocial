<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
unset($a);
	
	include ('top/header2.php'); 

	//extract all the data
	$id = $_GET['id'];
	$uid = $_SESSION['uid'];
 
	
	if ((!$id) || ($id == 0) || ($id == $_SESSION['uid'])) {	//show my profile
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php">';
		exit;		
	}
	
	include_once("utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	//check the BLOCK
	$get_block = mysql_query("SELECT COUNT(user1) FROM block WHERE (user1='$uid' AND user2='$id')OR(user1='$id' AND user2='$uid') ", $mysql_link);	//are there any blocks?
	$row = mysql_fetch_row($get_block); 
	$num_block = $row[0];
	if ($num_block > 0) {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php">';
		exit;
	 }	
	unset($row, $get_block, $num_block);
	////check the BLOCK	
	
	$get_pictures = mysql_query("SELECT COUNT(pic_id) FROM picture WHERE uid='$id' ", $mysql_link);	//number of pictures
	$row = mysql_fetch_row($get_pictures); 
	$num_pictures = $row[0];
	if (!$get_pictures || $num_pictures==0) {
		//disregard
	 } else {
			if ($num_pictures>4) {
				$startpic = $num_pictures - 4;
			} elseif ($num_pictures>0)
				$startpic = 0;
			$get_pictures = mysql_query("SELECT pic_id, picture FROM picture WHERE uid='$id' LIMIT $startpic, 4", $mysql_link);	//get 4 pictures
			if (!$get_pictures) {
				//disregard
			 }
			 $num = 0;
			unset($tumb_i,$tumb_p);
			while($row = mysql_fetch_array($get_pictures)) {
				$tumb_i[$num] = $row['pic_id'];
				$tumb_p[$num++] = $row['picture'];
			}
	 }
	 
	unset($row, $num_pictures, $get_pictures);
	 
	include_once("utils/online.php");		// UPDATE ONLINE STATUS
	include_once("utils/checkonline.php");	// GET ONLINE STATUS

	$rz = mysql_query("SELECT * FROM profile WHERE uid='$id'", $mysql_link);
	$row = mysql_fetch_array($rz);
	//mysql_close($mysql_link);
	
	if (empty($row)) {
		// no  such user
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php">';
		exit;		
	 }
		
	$uname = $row['name'];
	$upicture = $row['picture'];
	?>
    <script type="text/javascript">
document.title = "<?php echo $uname; ?>";
</script>
<?php
	
	include_once("utils/loading.php"); //loading profile data

	unset($row);
	
	$smile = $_GET['smile_sent'];
	$css = $_SESSION['css']; 
	
	
?>

<div id="left" style="min-height:750px;">
<!-- empty field -->

<h2 class="name"><?php echo $uname; ?><?php echo $online; ?></h2>

<?php 
//See if added to contacts already
	$get_contact = mysql_query("SELECT * FROM friends WHERE (user1='$uid' AND user2='$id' AND type='0')", $mysql_link);	//is he in your contacts
	$row3 = mysql_fetch_row($get_contact); 
	$contact = $row3[0];
	//They are Contacts
	if ($contact > 0) {
		echo '<p class="incontact">In your contacts</p>';
	}else{ //Display add Contacts 
		echo '<a href="javascript: addToContacts()" id = "contact_link">Add to Contacts</a>';
		echo '<p class="incontact" id="incontact" style="display:none;">In your contacts</p>';
	}
	mysql_close($mysql_link);
?>

<a href="profile.php?id=<?php echo $id; ?>">
<img src="<?php echo "pictures/".$upicture."170.jpg"; ?>" alt="My Face" width="170" height="220" class="profile_picture" />
</a>
<?php
	unset($uname, $upicture);
?>
<ul class="leftmenu">
    <li class="one"><a href="profile.php?id=<?php echo $id; ?>"><img src="images/<?php if ($css=='1')echo"minimal/";if ($css=='2')echo"alt/";?>profile_icon.png" class="icon">Profile</a></li>
    <li class="three"><a href="messages.php?to=<?php echo $id; ?>"><img src="images/<?php if ($css=='1')echo"minimal/";if ($css=='2')echo"alt/";?>message_icon.png" class="icon">Send message</a></li>
    <?php if (!$smile) : ?>
    <li class="two"><a href="smile.php?to=<?php echo $id; ?>"><img src="images/<?php if ($css=='1')echo"minimal/";if ($css=='2')echo"alt/";?>smile_icon.png" class="icon">Send smile</a></li>
    <?php else: ?>
    <li class="grey"><a href=""><img src="images/<?php if ($css==1)echo"minimal/";if ($css=='2')echo"alt/";?>smile_icon.png" class="icon">Smile sent!</a></li>
    <?php endif; ?>
    <li class="four"><a href="pictures.php?id=<?php echo $id; ?>"><img src="images/<?php if ($css=='1')echo"minimal/";if ($css=='2')echo"alt/";?>picture_icon.png" class="icon">Pictures</a></li>
</ul>

</div> <!-- left -->



<div id="right" style="min-height:750px;">
<p><span class="report">
	<a href="/report.php?uid=<?php echo $uid; ?>&id=<?php echo $id;?>">
	<img src="/images/report.png" border="0px" />
    report/block
	</a><br />
    <a href="/utils/random.php">show random profile</a>
    </span></p>

<div id="profile">
<h2><?php echo $headline; ?>&nbsp;</h2>

<?php	
	if ($num>0):
		echo "<div id=\"picturebar\">\r\n";
		for ($i = 0; $i < $num; $i++){
			echo "<a href=\"pictures.php?id=$id&display=$tumb_i[$i]\">";
			echo "<img src=\"$tumb_p[$i]_sml.jpg\" border=\"1px\" />";
			echo "</a>\r\n";
		}
		echo "</div>\r\n\r\n";
	endif;
?>

<?php 

include_once("utils/profile_view.php");

 ?>
 
<script type="text/javascript">
function addToContacts(){
	$.post("utils/addtocontacts.php", {myid: <?php echo $uid;?>, id: <?php echo $id;?>});
	 $('#contact_link').fadeOut('slow', function() {
        document.getElementById('contact_link').style.display = "none";
		document.getElementById('incontact').style.display = "inline";
		$('#incontact').fadeIn('slow');
      });
	 
	 
	
}
</script>
</div> <!-- profile -->
</div> <!-- right -->


<?php 
include ('footer.php'); 
?>
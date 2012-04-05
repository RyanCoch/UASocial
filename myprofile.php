<?php
$a = session_id();
if(empty($a)) session_start();
include ("top/header2.php");
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}


	//extract all the data
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	$picture = $_SESSION['picture'];
	$active = $_SESSION['active'];
		
	include_once("utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	$get_piccomments = mysql_query("SELECT COUNT(cid) FROM pic_com WHERE uid='$uid'", $mysql_link);
	if (!$get_piccomments) {
		//disregard
	 }
	$row = mysql_fetch_row($get_piccomments); 
	$num_piccomments = $row[0];
	
	$get_pictures = mysql_query("SELECT COUNT(pic_id) FROM picture WHERE uid='$uid' ", $mysql_link);	//number of pictures
	$row = mysql_fetch_row($get_pictures); 
	$num_pictures = $row[0];
	if (!$get_pictures || $num_pictures==0 ) {
		//disregard
	 } else {
			if ($num_pictures>4) {
				$startpic = $num_pictures - 4;
			} elseif ($num_pictures>0)
				$startpic = 0;
			$get_pictures = mysql_query("SELECT pic_id, picture FROM picture WHERE uid='$uid' ORDER BY pic_id ASC LIMIT $startpic, 4", $mysql_link);	//get 4 pictures
			if (!$get_pictures) {
				//disregard
			 }
			 $num = 0;
			while($row = mysql_fetch_array($get_pictures)) {
				$tumb_i[$num] = $row['pic_id'];
				$tumb_p[$num++] = $row['picture'];
			}
	 } //else
	
	include_once("utils/online.php");		// UPDATE ONLINE STATUS
	
	$query = "SELECT * FROM profile ";
	$query .= "WHERE uid='$uid'";// AND password='$pwd'";
	$result = mysql_query($query, $mysql_link);
	$row = mysql_fetch_array($result);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//something went very very wrong!
		 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=unknown">';
		 exit;
	 }

	 include_once("utils/loading.php"); //loading profile data	 
	
?>

<div id="left" style="min-height:750px;">

<?php include("utils/leftmenu.php");?>

</div> <!-- left -->




<div id="right" style="min-height:750px;">

<div id="profile">

<div id="contact_button">
	<img src="images/alt/contacts.png" width="71" height="19" alt="contacts" onclick="javascript:show_contacts();" />
</div>

<div id="contact_list" style="display:none;">
	<iframe src="contacts.php" id="c_frame" width="200px" scrolling="none" frameborder="0">
	<p>Your browser does not support inline frames.</p>
	</iframe>

</div>


<h2>My profile</h2>
<?php //if ($active==-1) :
	//echo "<p class=\"error\" style=\"line-height:1.2em;\"><strong>Warning!</strong><br>Your profile has been reported. It is under investigation!<br>";
	//echo "No action is required. You can continue using this website.</p>";
	//endif;
?>

<?php
	if ($num>0):
		echo "<div id=\"picturebar\">\r\n";
		for ($i = 0; $i < $num; $i++){
			echo "<a href=\"pictures.php?id=$uid&display=$tumb_i[$i]\">";
			echo "<img src=\"$tumb_p[$i]_sml.jpg\" border=\"1px\" />";
			echo "</a>\r\n";
		}
		echo "</div>\r\n\r\n<hr>";
	endif;
?>



<h2><?php echo $headline; ?></h2>

 <?php include_once("utils/profile_view.php"); ?>

</div> <!-- profile -->
</div> <!-- right -->

<script type="text/javascript">

var mouseX = 0;
var mouseY = 0;
var vis = false;

jQuery(document).ready(function(){
   $(document).mousemove(function(e){
      mouseX = e.pageX;
	  mouseY = e.pageY;
   }); 
})

function show_contacts() {
	if (!vis) {
	document.getElementById('contact_list').style.left = mouseX-150+"px";
	//document.getElementById('contact_list').style.display = "inline";
	$('#contact_list').fadeIn("fast");
	vis = !vis;
	} else {
	$('#contact_list').fadeOut("fast");
	vis = !vis;
	}
}

<?php
// frame size
$fheight = 20 * 2;
if (!isset($_SESSION['num_contacts'])) $_SESSION['num_contacts']=2;
$fheight += $_SESSION['num_contacts']*48;
if ($fheight>400)$fheight=400;
?>

document.getElementById("c_frame").style.height = "<?php echo $fheight."px";?>";

</script>

<?php
include ("footer.php");
mysql_close($mysql_link);
?>

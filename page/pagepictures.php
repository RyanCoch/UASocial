<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
unset($a);
	include ('../top/header.php'); 
	include_once("../utils/functions.php");
 	$uid = $_SESSION['uid'];	//my info
	$name = $_SESSION['name'];
	$myname = $name;
	$picture = $_SESSION['picture'];
	$pid = $_POST['id'];
	$id = $_GET['id'];				// display other user's pictures
	if (!$id) $id=$pid;
	$upload = $_GET['upload'];		// uploading new picture
	if (!$upload) {$upload = '0';}
	$upload_picture = $_POST['upload_picture'];		// uploading new picture
	if ($upload_picture=='yes'){$upload = '2';}
	
	$comment = $_GET['comment'];
	if (!$comment) $comment = $_POST['comment'];
	$like = $_GET['like'];
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
		
	include_once("../utils/online.php");		// UPDATE MY ONLINE STATUS
	//if($upload == 0){
	$rz = mysql_query("SELECT * FROM `page` WHERE `pid` = '$id' OR `pid` = '$pid' ", $mysql_link);
	$rowp = mysql_fetch_array($rz);
	
	if (empty($rowp)) {
		// no  such page
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php">';
		exit;		
	 }
	$pname = $rowp['name'];
	$ppicture = $rowp['picture'];
	//}
	?>
    <script type="text/javascript">
		document.title = "<?php echo $pname; ?>";
	</script>
    <?php
	
	//if we updating some picture
	$pic_id = $_POST['pic_id'];
	$cap = addslashes(strip_tags($_POST['cap']));
	$delete	= $_POST['delete'];
	$cmt = addslashes(strip_tags($_POST['cmt']));	

	//deleting OR editing the picture
	if ($pic_id):
	$profile = $_POST['profile'];
		if ($delete == '1') {
			$result = mysql_query("SELECT `picture` FROM `pagepicture` WHERE `pid` = '$id' AND `pic_id` = '$pic_id' ", $mysql_link);
			if (!$result) {
				//		echo "<br /><p class=\"error\">Cannot delete the picture at this time.</p>";
			 } else {
				$row = mysql_fetch_array($result); 
				$myFile[0] = inner()."page/".$row['picture']."_sml.jpg";
				unlink($myFile[0]);
				$myFile[1] = inner()."page/".$row['picture'].".jpg";
				unlink($myFile[1]);
			 }
			$result = mysql_query("DELETE FROM `pagepicture` WHERE `pid` = '$id' AND `pic_id` = '$pic_id' ", $mysql_link);
			if (!$result) {
				//		echo "<br /><p class=\"error\">Cannot delete the picture at this time.</p>";
			 }
		} elseif(!$profile)  {
			$result = mysql_query("UPDATE `pagepicture` SET `text` = '$cap' WHERE `pid` = '$id' AND `pic_id` = '$pic_id' ", $mysql_link) or die(mysql_error());
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot update the picture at this time.</p>";
			 }
		}
		if ($profile) {
			
			// MAKE PROFILE PICTURE
			
			$result = mysql_query("SELECT `picture` FROM `pagepicture` WHERE `pid` = '$id' AND `pic_id` = '$pic_id' ", $mysql_link);
			if (!$result) {
				echo "<br /><p class=\"error\">Cannot change profile picture at this time.</p>";
			 } else {
				$row = mysql_fetch_array($result); 
				//$myFile  = getcwd()."/".$row['picture'].".jpg";
				$picture = substr($row['picture'],9);
				$result = mysql_query("UPDATE `page` SET `picture` = '$picture' WHERE `pid` = '$id' ", $mysql_link) or die(mysql_error());
				$src = inner()."page/".$row['picture'].".jpg";
				
				$image = imagecreatefromjpeg($src);
				list($width,$height) = getimagesize($src);
						$new_size_x = 60;
						$new_size_y = 60;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 		//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) { 		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."60.jpg");
						imagedestroy($image_p);
						$new_size_x = 30;
						$new_size_y = 30;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 	//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) {		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."30.jpg");
						imagedestroy($image_p);
						$new_size_x = 170;
						$new_size_y = 220;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);		
						if ($width >= $height) { 	//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) {		//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);		
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,$row['picture']."170.jpg");
						imagedestroy($image_p);
						imagedestroy($image);			
			
			echo "<form method=\"post\" name=\"prof_pic\" action=\"/page/pagesettings.php?update=3&id=".$id."\">";
			echo "</form>";	
			
			echo "<script type=\"text/javascript\">";
			echo "function submitform(){\r\n";
			echo "document.forms[\"prof_pic\"].submit();\r\n";
			echo "}\r\n submitform();\r\n</script>";
			 }
		}
	endif; ////deleted or edited
?>

<div id="left" style="min-height:750px;">

<h2 class="name"><?php echo $pname; ?></h2>

<?php 
//See if added to contacts already
	$get_contact = mysql_query("SELECT * FROM friends WHERE (user1='$uid' AND user2='$id' AND type='1') ", $mysql_link);	//is page in your contacts
	$row3 = mysql_fetch_row($get_contact); 
	$contact = $row3[0];
	//They are Contacts
	if ($contact > 0) {
		echo '<p class="incontact">In your contacts</p>';
	}else{ //Display add Contacts 
		echo '<a href="javascript: addPageToContacts()" id = "contact_link">Add to Contacts</a>';
		echo '<p class="incontact" id="incontact" style="display:none;">In your contacts</p>';
	}
?>

<a href="./?id=<?php echo $id; ?>">
<img src="<?php echo "pictures/".$ppicture."170.jpg"; ?>" alt="Page Picture" width="170" height="220" class="profile_picture" />
</a>

<?php
//	unset($pname, $ppicture);
//	$contact_id = getFirstAdmin($rowp['admins']);
	$imadmin = isAdmin($rowp['admins'],$uid);	// checks if i'm admin of this page
	$firstAdmin = getFirstAdmin($rowp['admins']);
	
?>
<ul class="leftmenu">
    <li><a href="./?id=<?php echo $id; ?>"><img src="../images/alt/profile_icon.png" class="icon">Page Profile</a></li>
<?php if (!$imadmin) :?>
    <li><a href="../messages.php?to=<?php echo $contact_id; ?>"><img src="../images/alt/message_icon.png" class="icon">Contact Page</a></li>
<?php endif; ?>
    <li><a href="pagepictures.php?id=<?php echo $id; ?>"><img src="../images/alt/picture_icon.png" class="icon">Pictures</a></li>
<?php if ($imadmin) :?>
    <li class="green"><a href="pagesettings.php?id=<?php echo $id; ?>"><img src="../images/alt/settings_icon.png" class="icon">Edit Page</a></li>
    <li class="green"><a href="pagecontacts.php?id=<?php echo $id; ?>"><img src="../images/alt/contacts_icon.png" class="icon">Contact List</a></li>
<?php endif; ?>
<script type="text/javascript">
function addPageToContacts(){
	$.post("../utils/addtocontacts.php", {myid: <?php echo $uid;?>, id: <?php echo $id;?>, type: 1});
	 $('#contact_link').fadeOut('slow', function() {
        document.getElementById('contact_link').style.display = "none";
		document.getElementById('incontact').style.display = "inline";
		$('#incontact').fadeIn('slow');
      });
}
</script>    
</ul>
<?php
echo "<div id=\"new_messages\">";
include (inner()."newmsg.php");
echo "</div>\r\n";
?>
</div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
    <h2>Pictures</h2> 
   
       <?php
	if ( $imadmin && $upload=='0' &&  !$display ) :		//if i'm looking at my own pictures

		echo "<span class=\"upper_bar\"><a href=\"pagepictures.php?upload=1&id=".$id."\">Upload new picture</a></span><br />\r\n\r\n";

		$query = "SELECT * FROM pagepicture WHERE pid='$id' ORDER BY pic_id ASC ";
		$result = mysql_query($query, $mysql_link);
		if ( (!$result) || (!mysql_num_rows($result)) ) {
			//cant find pictures
			 mysql_close($mysql_link);
			 echo "<br /><p class=\"error\">Your page doesn't have any pictures in the album.</p>";
		 } else {
			 // if I have pictures
			
			while($row = mysql_fetch_array($result)) {				//go through the dataset
				echo "<div class=\"pic\">\r\n";
				echo "<a href=\"pagepictures.php?id=".$id."&display=".$row['pic_id']."\">";
				echo "<img src=\"".$row['picture']."_sml.jpg\" border=\"1px\" align=\"left\">";
				echo "</a>\r\n";
				echo "<form method=\"post\" action=\"pagepictures.php?id=".$id."\">";
				echo "<input type=\"hidden\" value=\"".$row['pic_id']."\" name=\"pic_id\"/>";
        		echo "<input type=\"text\" class=\"tpic\" name=\"cap\" maxlength=\"255\" style=\"width:300px;\" value=\"".$row['text']."\" /> <br/>";
				echo "<input type=\"checkbox\" class=\"styled3\" value=\"1\" name=\"delete\"/> Delete &nbsp;&nbsp;&nbsp;";
				echo "<input type=\"checkbox\" class=\"styled3\" value=\"1\" name=\"profile\"/> Make page picture <br />";
				echo "<input type=\"hidden\" name=\"id\" value=\"$id\"/></td>\r\n";
				echo "<button type=\"submit\" class=\"bpicture\" value=\"Update\" />Update</button>";
				echo "</form>";
				echo "</div>\r\n";
				
			}// WHILE LOOP
			mysql_close($mysql_link);
		 }
		
 	elseif ( strlen($id)>0 && $upload=='0' || (!$imadmin && $upload == 1)) :		//if i'm looking at somebody's pictures
	
		if ($imadmin)
			echo "<span class=\"upper_bar\"><a href=\"pagepictures.php?upload=1&id=".$id."\">Upload new picture</a><a href=\"pagepictures.php?id=".$id."\">My pictures</a><a href=\"../page/?id=".$id."\">My page</a></span><br />\r\n\r\n";
	
		$query = "SELECT * FROM pagepicture WHERE pid='$id' ORDER BY pic_id ASC  ";
		$result = mysql_query($query, $mysql_link);
		if ( (!$result) || (!mysql_num_rows($result)) ) {
			 mysql_close($mysql_link);
			 echo "<p class=\"error\">This page doesn't have any pictures in their album.</p>";
		 } else {
			 // if he has pictures
			$display = $_GET['display'];
			$num = 1;
			echo "<div id=\"beautiful_pic2\">\r\n";
			while($row = mysql_fetch_array($result)) {				//go through the dataset
				if (!$display) {$display = $row['pic_id']; $say = $row['pic_id'];}
				if (is_numeric($display) && $display == $row['pic_id']) {
					$pic_id = $display;
					$catchnext=1;
					$display = $row['picture'];
					$cap = $row['text'];
					if ($num == 1) $dt = 1;
					$dn = $num;
					echo "<a class=\"act\" href=\"pagepictures.php?id=".$id."&display=".$row['pic_id']."\">".$num++."</a>";
				} else {
				echo "<a href=\"pagepictures.php?id=".$id."&display=".$row['pic_id']."\">".$num++."</a>";
					if (is_numeric($display)) $lastimg = $row['pic_id'];
					if ($catchnext) {$nextimg = $row['pic_id']; $catchnext=0;}
				}
			}// WHILE LOOP
			echo "<span style=\"clear:both;\">&nbsp;</span>";
			echo "</div>\r\n";
			if (($num - 1) == $dn) $dt += 2;	//if dt==1 no prev picture, if dt==2 no next, if dt == 3 no next, no prev
			echo "<div id=\"big_picture\">";
			list($width,$height) = getimagesize($display.".jpg");	
			?>
  
            <script type="text/javascript">
			function next() {
			window.location = "<?php echo "pagepictures.php?id=".$id."&display=".$nextimg;?>";
			}
			function prev() {
			window.location = "<?php echo "pagepictures.php?id=".$id."&display=".$lastimg;?>";
			}
			</script>
            
            <center>
			<table width="<?php echo $width;?>px" height="<?php echo $height;?>px" cellspacing="0" cellpadding="0" style="margin-top:10px;background-image: url('<?php echo $display.".jpg"?>');" >
			<tr><td width="50px" <?php if($dt==0 || $dt==2) echo "onMouseOver=\"document.getElementById('leftimg').src='../images/left.png';\"";?> onMouseOut="document.getElementById('leftimg').src='../images/transparent.png';" onclick="javascipt:prev();" align="center" valign="middle">
            <img src="../images/transparent.png" width="50" height="50" border="0" id="leftimg" />
			</td><td><a href="javascript:next();"><img src="../images/transparent.png" width="<?php if(($width-100)>0) echo $width-100; else echo "0";?>" height="<?php echo $height;?>" border="0" id="leftimg" /></a></td>
            <td width="50px"  <?php if($dt==0 || $dt==1) echo "onMouseOver=\"document.getElementById('rightimg').src='../images/right.png';\"";?> onMouseOut="document.getElementById('rightimg').src='../images/transparent.png';" onclick="javascipt:next();" align="center" valign="middle">
            <img src="../images/transparent.png" width="50" height="50" border="0" id="rightimg" />
			</td></tr>
			</table>
            </center>
			<?php
			echo "<br /><span>".$cap."</span></div>\r\n";			
			
			// COMMENTS THING
			
			?>
       
            <script type="text/javascript">
				function displayIt() {
					document.getElementById("comment").style.display = "inline";					
				}		
			</script>
            
           
            <a class="fb" href="javascript:displayIt();"><img src="../images/leavecomment.png" class="icon">Leave a comment&nbsp;</a> <a class="fb" href="pagepictures.php?like=<?php echo $uid;?>&id=<?php echo $id;?>&display=<?php echo "$pic_id"; ?>"><img src="../images/like.png" class="icon">Like&nbsp;</a><br />
            <div id="comment" style="display:none;">
            	<form method="post" id="comment_form" action="pagepictures.php?id=<?php echo "$id"; ?>&display=<?php echo "$pic_id"; ?>">
                <input type="hidden" name="comment" value="say" />
            	<textarea class="tpic" name="cmt" id="cmt" maxlength="255" style="width:460px;" rows="3"></textarea>
                <button class="bpicture" type="submit" style="position:relative; top:-13px; margin-left:10px;">Say</button>
            </div>
			
            <?php

			if ($like) :
				//check if i liked it before
					$ismypicture = mysql_query("SELECT COUNT(cid) FROM pic_com WHERE uid='$uid' AND type='4' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
					$row = mysql_fetch_row($ismypicture); 
					$ismypicture = $row[0];
					if ($ismypicture==0) {// LIKE	
						$result = mysql_query("INSERT INTO `pic_com`(`pic_id`, `uid`, `type`) VALUES ('$pic_id','$uid','4') ", $mysql_link) or die(mysql_error());
					} else { //UNLIKE						
						 $result = mysql_query("DELETE FROM `pic_com` WHERE type='4' AND uid='$uid' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
						}
			endif; //like

			
			if ($comment == "say") :
				$today = date("Y-m-d H:m:s");
				$result = mysql_query("INSERT INTO `pic_com`(`pic_id`, `uid`, `type`, `comment`, `datetime`) VALUES ('$pic_id','$uid','3','$cmt','$today') ", $mysql_link) or die(mysql_error());
				
				if ( strlen($id)>0) { // not my own picture
				
					//send notification email
					$today = date("Y-m-d H:i:s");
					$subject = "Comment notification";
					$text = "Authomatic notification:<br /><br />$myname has just commented on your page.<br />";
					$text .= "To see the comment, please follow the ";
					$text .= "<a href=\"/page/pagepictures.php?id=$id&display=$pic_id\">link</a><br /><br />";
					$query =  mysql_query("INSERT INTO message (mid, from_uid, to_uid, didread, subject, text, type, disp_s, datetime) VALUES  ('0','$uid','$firstAdmin','0','$subject','$text', '1', '0', '$today')", $mysql_link) or die(mysql_error());				
				}
			endif; //say

			if ($comment == "erase" && ($imadmin)) :
				$cid = $_GET['cid'];
				
				//check if i have rights to delete this comment
					$ismypicture = mysql_query("SELECT COUNT(pic_id) FROM pagepicture WHERE pid='$id' AND pic_id='$pic_id' ", $mysql_link) or die(mysql_error());
					$row = mysql_fetch_row($ismypicture); 
					$ismypicture = $row[0];
					if ($ismypicture>0)				
						$result = mysql_query("DELETE FROM `pic_com` WHERE cid = '$cid' ", $mysql_link) or die(mysql_error());
			endif; //erase

				// display like
				$query = mysql_query("SELECT profile.name, profile.uid, pic_com.* FROM profile, pic_com WHERE pic_com.pic_id='$pic_id' AND profile.uid = pic_com.uid AND pic_com.type = '4'", $mysql_link) or die(mysql_error());
				echo "<div id=\"like\">";
				$i = 0;
				while ($row = mysql_fetch_array($query)) {
					if ($i>0)echo ", ";
					echo "<a class=\"like\" href=\"profile.php?id=".$row['uid']."\">".$row['name']."</a>";
					$i++;
				}
				if($i>0)echo " likes this.";
				echo "</div>\r\n";
			
				// display comments
				$query = mysql_query("SELECT profile.name, profile.uid, profile.picture, pic_com.* FROM profile, pic_com WHERE pic_com.pic_id='$pic_id' AND profile.uid = pic_com.uid AND pic_com.type = '3' ORDER BY pic_com.datetime ASC ", $mysql_link) or die(mysql_error());
				echo "<br />";
				while ($row = mysql_fetch_array($query)) {
					$today = date('M, j h:i A', strtotime($row['datetime']));	
					echo "<div class=\"comment\">";
					echo "<img src=\"../pictures/".$row['picture']."30.jpg\" align=\"left\"/>";
					echo "<a class=\"fb\" href=\"profile.php?id=".$row['uid']."\">".$row['name']."</a>";
					echo "<span class=\"date\">".$today.(($imadmin)?" <a href=\"pagepictures.php?id=$id&display=$pic_id&comment=erase&cid=".$row['cid']."\">remove</a>":"")."</span>";
					echo "<br /><span class=\"uname\">".nl2br($row['comment'])."</span><br>";
					echo "</div>\r\n";
					echo "<br />";
				}

			mysql_close($mysql_link);			
		 }
	
	

	endif;
	
	if ( $imadmin && $upload == '1' || $upload == '2'):			//if going to upload
		
	?>
   
    <div id="beautiful_pic">
		<form method="POST" enctype="multipart/form-data" name="image_upload_form" action="pagepictures.php?upload=1&id=<?php echo $id; ?>>">
			<label class="long"><strong>Please choose the picture</strong><br /><small>(Maximum size: 4Mb)</small></label><br /><br />
			<input type="hidden" name="upload_picture" value="yes"/>
            <input type="hidden" name="id" size="60" value="<?php echo $id; ?>"/>
			<input type="hidden" name="MAX_FILE_SIZE" value="4000000">
			<input type="file" name="image_file" style="width:400px;"><br /><br />
        <p>By uploading your picture, you expressly agree to the <a href="terms.php">Terms and Conditions</a>.<br />
        Pictures violating Terms and Conditions will be expelled from the site. Corresponding user account will be blocked.</p>      
			<button class="bpicture" type="submit" value="Upload picture" name="action">Upload picture</button><br />
		</form> 
        
     </div>
    <?php
	endif;

	if ($upload == '2'):			//if uploading the picture
		unset($imagename);
		if(!isset($_FILES) && isset($HTTP_POST_FILES))
			$_FILES = $HTTP_POST_FILES;
		if(!isset($_FILES['image_file']))
			$error["image_file"] = "An image was not found.";
		$imagename = basename($_FILES['image_file']['name']);
		
		if (end(explode(".", strtolower($_FILES['image_file']['name']))) != 'jpg')
			$error["ext"] = "Invalid file type. Only jpeg files allowed.";		
		//echo "a. ".$imagename."<br>";													//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
		if(empty($imagename))
			$error["imagename"] = "The name of the image was not found.";
		if(empty($error))
			{
				$newimage = getcwd()."/pictures/".$pid."/";
				$thisdir = $newimage; 
				if (!file_exists($newimage) || !is_dir($newimage)) {					
					if(!mkdir($thisdir, 0777))
						$error["directory"] = "Unable to create user directory.";
				}
				$gen_name = genRandomString();				
				$newimage = $thisdir.$gen_name.".jpg";
				//echo "b. ".$newimage."<br>";												//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
				//echo "c. ".$_FILES['image_file']['tmp_name']."<br>";						//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!111
				$result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
				if(empty($result))
					$error["result"] = "There was an error moving the uploaded file.";
			}
		   if (!empty($error)) {
			  echo "<p class=\"error\">".$error["image_file"].$error["imagename"].$error["directory"].$error["result"].$error["ext"]."</p>";
			  echo "</div></div>";
			  include ("../footer.php");
			  exit;
		   }

		$src =	$newimage;
		if (file_exists($src)){
			ini_set('memory_limit','64M');
			$image = imagecreatefromjpeg($src);
			if(!$image)
				echo "<p class=\"error\">File is too big, or unknown error.</p>";
			list($width,$height) = getimagesize($src);	
					
			//create the large picture
			$new_size_x = 560;
			$new_size_y = 700;
			if($width>$new_size_x || $height>$new_size_y) {	// do we actually need to make the file smaller
				$koef = ($width/$height);
				if ($width >= $height) { 		//horizontal
					$new_size_x = 560;					
					$new_size_y = $new_size_x / $koef;		
				} else {
					//vertical
					$new_size_y = 700;
					$new_size_x = $new_size_y * $koef;	
				}
				$image_p = imagecreatetruecolor($new_size_x,$new_size_y);
				$white = imagecolorallocate($image_p, 255, 255, 255);
				imagefilledrectangle($image_p, 0, 0, $new_size_x, $new_size_y, $white);			
				imagecopyresampled($image_p,$image,0,0,0,0,$new_size_x,$new_size_y,$width,$height);
				imagejpeg($image_p,$src);	// save file
				imagedestroy($image_p);	
			}		
		}						
			// let user cut the small picture
			// 140 x 100
			?>
            
   		<script src="../js/jquery.min.js"></script>
		<script src="../js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
		
		<script language="Javascript">
			$(function(){
				$('#cropbox').Jcrop({
					aspectRatio: 1.4,
					bgColor:     'black',
					setSelect:   [ 0, 0, 550, 550 ],
					onSelect: updateCoords
				});
			});
			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};
			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press save.');
				return false;
			};
		</script>
		
        <p>Please choose a region to make a thumbnail.</p>
        <center>
		<img class="prof" src="<?php echo $urladdress."/page/pictures/".$pid."/".$gen_name.".jpg?".time();?>" id="cropbox" />
        </center>
            <form method="post" action="pagepictures.php?upload=4&id=<?php echo $pid; ?>"  onSubmit="return checkCoords();">
            <input type="hidden" name="src" value="<?php echo $gen_name;?>" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
            <button class="bpicture" value="Save">Save</button>
            </form>
            <br />
			<br />

            <?php	
			endif;
		
		if ($upload=='4') { //user finished cutting the picture	
				
				$targ_w = 140;
				$targ_h = 100;
				$jpeg_quality = 90;
			
				$newimage = getcwd()."/pictures/".$id."/";
				$gen_name = $_POST['src'];
				$src = $newimage.$gen_name.".jpg";
				$img_r = imagecreatefromjpeg($src);
				$dst_r = imagecreatetruecolor($targ_w, $targ_h );
			
				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
				//header('Content-type: image/jpeg');
				imagejpeg($dst_r,$newimage.$gen_name."_sml.jpg",$jpeg_quality);
				
		$query="INSERT INTO `pagepicture` (`pic_id`, `pid`, `picture`, `text`, `type`)  VALUES ('0','$id','pictures/$id/$gen_name','', '0')";
 		$result = mysql_query($query, $mysql_link);
		mysql_close($mysql_link);
			if (!$result) {
				echo "<p class=\"error\">Unable to save picture! <a href=\"pagepictures.php?id=".$id."\">Return</a></p>";			
			} else {
				echo "<p class=\"error\">Picture was uploaded successfully. <a href=\"pagepictures.php?id=".$id."\">Return</a></p>";
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=pagepictures.php?id='.$id.'">';
			}
			
		}
 ?>
 
    </div></div>
<?php
include ("../footer.php");
	//mysql_close($mysql_link);
?>
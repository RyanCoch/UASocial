<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}
unset($a);
	
	include ('../top/header.php'); 

	//extract all the data
	$id = $_GET['id'];
	if (!$id)
		$id = $_POST['id'];
	$uid = $_SESSION['uid'];
	$pid = $_POST['id'];
 	
	if ((!$id) || ($id == 0)) {	//id of page is not set
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
		exit;		
	}
	
	include_once(inner()."utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	include_once(inner()."utils/online.php");		// UPDATE MY ONLINE STATUS

	$rz = mysql_query("SELECT * FROM `page` WHERE (`pid` = '$id' OR `pid` = '$pid') ", $mysql_link);
	$rowp = mysql_fetch_array($rz);
	//mysql_close($mysql_link);
	
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
<div id="settings">
<h2>Page Settings</h2>


<?php
$update = $_GET['update'];
if (!$update) $update = $_POST['update'];
		
	if ($update=="2") {
		//uploading the picture
		unset($imagename);
		
		if(!isset($_FILES) && isset($HTTP_POST_FILES))
			$_FILES = $HTTP_POST_FILES;
		if(!isset($_FILES['image_file']))
			$error["image_file"] = "An image was not found.";
		$imagename = basename($_FILES['image_file']['name']);
		if (end(explode(".", strtolower($_FILES['image_file']['name']))) != 'jpg')
			$error["ext"] = "Invalid file type. Only jpeg files allowed.";		
		
		//echo $imagename;
		if(empty($imagename))
			$error["imagename"] = "The name of the image was not found.";
		if(empty($error))
			{
				$newimage = inner()."page/pictures/".$id."/";
				$thisdir = $newimage; 
				if (!file_exists($newimage) || !is_dir($newimage)) {
					
					if(!mkdir($thisdir, 0777)) {
						$error["directory"] = "Unable to create user directory.";
					}
				}
				$gen_num = genRandomString();
				$newimage = $thisdir.$gen_num.".jpg";
				//echo $newimage;
				//echo $_FILES['image_file']['tmp_name'];
				$result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
				if(empty($result))
					$error["result"] = "There was an error moving the uploaded file.";
			}
		   if (!empty($error)) 
			{
			  // if an error occurs the file could not
			  // be written, read or possibly does not exist
			   echo "<p class=\"error\">".$error["image_file"].$error["imagename"].$error["directory"].$error["result"].$error["ext"]."</p>";
		   } else {
		
					$src =	$newimage;
					if (file_exists($src)){
						ini_set('memory_limit','64M');
						
						$new_size_x = 540;
						$new_size_y = 700;
						list($width,$height) = getimagesize($src);
						$image = imagecreatefromjpeg($src);
						if(!$image) {
							 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/page/pagesettings.php?id='.$id.'">';
							 exit;
							}			
						if($width>$new_size_x || $height>$new_size_y) {	// do we actually need to make the file smaller
							$koef = ($width/$height);
							if ($width >= $height) { 		//horizontal
								$new_size_x = 540;					
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
						
						$new_size_x = 60;
						$new_size_y = 60;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);
						$white = imagecolorallocate($image_p, 255, 255, 255);
						imagefilledrectangle($image_p, 0, 0, $new_size_x, $new_size_y, $white);			
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
						imagejpeg($image_p,inner()."page/pictures/".$id."/".$gen_num."60.jpg");
						imagedestroy($image_p);
						$new_size_x = 30;
						$new_size_y = 30;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);
						$white = imagecolorallocate($image_p, 255, 255, 255);
						imagefilledrectangle($image_p, 0, 0, $new_size_x, $new_size_y, $white);			
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
						imagejpeg($image_p,inner()."page/pictures/".$id."/".$gen_num."30.jpg");
						imagedestroy($image_p);
						$new_size_x = 170;
						$new_size_y = 220;
						$image_p = imagecreatetruecolor($new_size_x,$new_size_y);
						$white = imagecolorallocate($image_p, 255, 255, 255);
						imagefilledrectangle($image_p, 0, 0, $new_size_x, $new_size_y, $white);			
						if ($width >= $height) { 	//horizontal
							$src_top = 0;
							$koef = $height/$new_size_y;
							$src_left = ($width-$new_size_x*$koef)/2;
							$src_h = $height;
							$src_w = $src_h*($new_size_x/$new_size_y);		
						}
						if ($width < $height) { 	//vertical
							$src_left = 0;
							$koef = $width/$new_size_x;
							$src_top = ($height-$new_size_y*$koef)/2;
							if ($src_top<0) {$diff=0-$src_top;$src_top=0;}
							$src_w = $width;
							$src_h = $src_w*($new_size_y/$new_size_x);	
							if ($diff>0)	{$src_h-=2*$diff;$src_w-=2*$diff;}
						}
						imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
						imagejpeg($image_p,inner()."page/pictures/".$id."/".$gen_num."170.jpg");
						imagedestroy($image_p);
						imagedestroy($image);
					}
					
					//$_SESSION['picture'] = "$id/".$gen_num;
					$picture = "$id/".$gen_num;
					$result = mysql_query("UPDATE `page` SET `picture` = '$id/$gen_num' WHERE `pid` = '$id'", $mysql_link) or die(mysql_error());
		   } // else
		}	

if ($update=="2") :	//continue
?>
					<div id="blue_frame" style="height:380px;">
                    <p><strong>Please review the new profile picture</strong></p><br /><center>
                    <img class="prof" style="max-width:520px;" src="/page/pictures/<?php echo $picture;?>170.jpg" />
                    <br /><br />
                    <a class="fb" href="/page/pagesettings.php?update=3&id=<?php echo $id; ?>">Crop the picture</a></center>
                    <form action="/page/pagesettings.php" style="float:right;">                    
                    <button class="bsettings" onclick="javascript:actpic();">Save</button>
                    </form>                    
                    
                    <span style="clear:both;">&nbsp;</span><br />

                    </div>                            
                            
 <?
							
	echo "</div></div>";
	include (inner()."top/footer.php");
	exit;	
	endif;	

if ($update=="3") :	//cropping the picture
?>
       
   		<script src="<?php echo $urladdress;?>/js/jquery.min.js"></script>
		<script src="<?php echo $urladdress;?>/js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="<?php echo $urladdress;?>/css/jquery.Jcrop.css" type="text/css" />
		
		<script language="Javascript">
			$(function(){
				$('#cropbox').Jcrop({
					aspectRatio: 0.773,
					bgColor:     'black',
					setSelect:   [ 0, 0, 500, 500 ],
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
					<br />
                    <p><strong>Please choose a region</strong></p><br />
                    <center>
                    <img class="prof" src="<?php echo $urladdress."/page/pictures/".$ppicture.".jpg";?>" id="cropbox" />
                    </center>
                        <form method="post" action="/page/pagesettings.php?update=4"  onSubmit="return checkCoords();">
                        <input type="hidden" name="src" value="<?php echo inner()."/page/pictures/".$ppicture;?>" />
                        <input type="hidden" name="id" value="<?php echo $id;?>" />
                        <input type="hidden" id="x" name="x" />
                        <input type="hidden" id="y" name="y" />
                        <input type="hidden" id="w" name="w" />
                        <input type="hidden" id="h" name="h" />
                        <button class="bsettings" value="Save" onclick="javascript:actpic();">Save</button>
                        </form>
                        <br />
 <?
	echo "</div></div>";
	include (inner()."top/footer.php");
	exit;	
	endif;	
if ($update=="4") :	//saving the cropped picture

				$targ_w = 170;
				$targ_h = 220;
				$jpeg_quality = 90;

				$src = $_POST['src'].".jpg";
				$img_r = imagecreatefromjpeg($src);
				$dst_r = imagecreatetruecolor($targ_w, $targ_h );
			
				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
				//header('Content-type: image/jpeg');
				imagejpeg($dst_r,$_POST['src']."170.jpg",$jpeg_quality);
				
				$src = $_POST['src']."170.jpg";
				list($width,$height) = getimagesize($src);
				$image = imagecreatefromjpeg($src);
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
						imagejpeg($image_p,$_POST['src']."60.jpg");
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
						imagejpeg($image_p,$_POST['src']."30.jpg");
						imagedestroy($image_p);
						imagedestroy($image);	

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/page/pagesettings.php?id='.$id.'">';							
	echo "</div></div>";
	include (inner()."top/footer.php");
	exit;	
	endif;					
	?>
<?php
//Update the fields
$update2 = $_POST['update2'];

if ($update2) {
	$newName = addslashes(trim(strip_tags($_POST['name'])));
	$newHead = addslashes(trim(strip_tags($_POST['headline'])));
	$newAbout = addslashes(trim(strip_tags($_POST['text'])));
	$newCat = addslashes(trim(strip_tags($_POST['catdropdown'])));
	$right = $_POST['right'];
	
	$upQuery = mysql_query("UPDATE `page` SET `name` = '$newName', `headline` = '$newHead', `about` = '$newAbout', `category` = '$newCat', `right` = '$right' WHERE `pid` = '$id' ", $mysql_link );

	//Extract the data 
	$rz = mysql_query("SELECT * FROM `page` WHERE `pid` = '$id' ", $mysql_link);
	$rowp = mysql_fetch_array($rz);
	
	$wasupdated = true;
}

	$pname = $rowp['name'];
	$phead = $rowp['headline'];
	$pabout = $rowp['about'];
	$picture = $rowp['picture'];
	$category = $rowp['category'];
	$right = $rowp['right'];
	
?>
    <script type="text/javascript">
		function getIE()
		// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
		{
		  var rv = -1; // Return value assumes failure.
		  if (navigator.appName == 'Microsoft Internet Explorer')
		  {
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
			  rv = parseFloat( RegExp.$1 );
		  }
		  return rv;
		}

	function display_form() {
		document.getElementById('profile_picture_form').style.position = "relative"
		document.getElementById('profile_picture_form').style.visibility = "visible";
		document.getElementById('temp').style.display = "none";
		if (getIE() == -1) {
			document.getElementById('image_file').style.visibility = "hidden";
			document.getElementById('fname').style.display = "inline";
			document.getElementById('image_file').click();
		}		
	}
	
	function changeFile() {
		str = document.getElementById('image_file').value;
		str = str.replace(/^.*\\/, '');
		document.getElementById('fname').innerHTML = str; 
		document.getElementById('profile_picture_form').submit();
	}

	</script>



<?php
	if ($wasupdated) {
		echo "<p class=\"error\">Changes have been saved.</p>";
	}
?>

   <div id="blue_frame">
    	<div id="temp">
            <img src="<?php echo "/page/pictures/".$ppicture."60.jpg";?>" width="60px" height="60px" class="prof"/>
            <button onclick="javascript:display_form();" style="float:right; margin:30px 260px 0 0;" class="bsettings">Update page picture</button>
        </div>
        <form style="visibility:hidden; position:absolute; left:0; padding:0; margin:0;" id="profile_picture_form" method="POST" enctype="multipart/form-data" name="image_upload_form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label class="long"><strong>Please choose the page picture</strong><br /><small>(Maximum size: 4Mb)</small></label><br />
            <input type="hidden" name="update" value="2"/>
             <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
            <input type="file" style="width:400px;" id="image_file" name="image_file" onchange="javascript:changeFile();"><br />
            <button type="submit" id="upload_button" class="bsettings" value="Upload Image" name="action">Upload picture</button>
            <label id="fname" style="display:none;"></label>
        </form>
        
    </div>
    
   <form method="post" name="page_update" action="/page/pagesettings.php?id=<?php echo $id; ?>">    
        <label class="bigger">Page Name</label>
        <input type="hidden" name="update2" size="60" value="1"/>
        <input type="text" name="name" maxlength="100" style="width:300px;" value="<?php echo $pname; ?>" /> <br/>
        <label class="bigger">Page HeadLine</label>
		<input type="text" name="headline"  maxlength="100" style="width:300px;" value="<?php echo $phead; ?>"  /> <br/>
        <label class="bigger">Category</label>
        <select name="catdropdown" style="width:250px;">
		<option value="Sorority" <?php if($category=='Sorority') echo "selected";?>>Sorority</option>
		<option value="Fraternity" <?php if($category=='Fraternity') echo "selected";?>>Fraternity</option>
		<option value="Student Group" <?php if($category=='Student Group') echo "selected";?>>Student Group</option>
        <option value="Website" <?php if($category=='Website') echo "selected";?>>Website</option>
        <option value="Entertainment" <?php if($category=='Entertainment') echo "selected";?>>Entertainment</option>
        <option value="Product" <?php if($category=='Product') echo "selected";?>>Product</option>
        <option value="Service" <?php if($category=='Service') echo "selected";?>>Service</option>
        <option value="Organization" <?php if($category=='Organization') echo "selected";?>>Organization</option>
		</select><br />
        <label class="bigger">Posting</label>
        <select name="right" style="width:250px;">
		<option value="0" <?php if($right=='0') echo "selected";?>>Only Admins can post on the wall</option>
		<option value="1" <?php if($right=='1') echo "selected";?>>Everyone can post on the wall</option>
		</select><br /><br />
        
        <!-- ADMINS -->
        <label class="bigger" id="adminLabel1">Administrators</label><br />
		<?php
			$query = "SELECT name, uid FROM `profile` WHERE `uid` = $uid ";
			$adList = explode(',',$rowp['admins']);
			foreach($adList as $al)
				$query .= "OR `uid` = '".$al."' ";
				
			$rz = mysql_query($query, $mysql_link);
			
			echo "<p id=\"adminLabel3\">";
			$notfirst = false;
			$fml = "";
			while($rowz = mysql_fetch_array($rz)) {
				if ($notfirst) echo ", ";
				echo "<a href=\"/profile.php?id=".$rowz['uid']."\">";		
				echo $rowz['name'];
				echo "</a>";
				$fml .= "<a target=\"_blank\" id=\"name".$rowz['uid']."\" style=\"display:inline-block;width:250px;\" href=\"/profile.php?id=".$rowz['uid']."\">".$rowz['name']."</a>";
				$fml .= "<a id=\"delete".$rowz['uid']."\" href=\"javascript:deleteadmin(".$rowz['uid'].");\">delete admin</a><br />";
				$notfirst = true;
			}
			echo "</p>\r\n";
		?>
		<a href="javascript:editAdmins();" id="adminLabel2">Edit list</a><br />
        <div id="adminEdit4" style="display:none;">
			<?php
				echo $fml;
            ?>
            <br />
            <label class="bigger">Add admin <br /><small>(search person by name)</small>:</label>&nbsp;
            <input type="text" maxlength="50" style="width:200px;" value="" id="searchname" />&nbsp;
            <a href="javascript:searchbyname();">Search</a>
            <div id="searchbox" style="padding:15px; font-size:1.1em;"></div>
        </div>
        <!-- END of ADMINS -->
        <br />
		<label style="width:400px;" id="atext">About Page</label><br /><br />
		<textarea name="text" id="about" maxlength="1000" onkeyup="javascript:textarea(1000, 'About Page ');" style="width:500px;" rows="8"><?php echo $pabout; ?></textarea><br />
        <button class="bpage" type="submit" value="Send">Save Settings</button>
    </form>

</div> <!-- Profile div -->
</div> <!-- Right div -->

<script type="text/javascript">
function searchbyname() {
		var line = document.getElementById('searchname').value;		
		$.ajax({
			url: "search.php?search="+line,
			cache: false,
			success: function(html){	
			$("#searchbox").html(html);	
		  	},
		});
}
</script>

<script type="text/javascript">
	textarea(1000, 'About Page ');
	
	function editAdmins() {
		$('#adminLabel2').fadeOut('fast');
		$('#adminLabel3').fadeOut('fast',function() {
        	$('#adminEdit4').fadeIn("fast");
    	});
	}	
</script>

<script type="text/javascript">
function addasadmin(id){
	$.post("makeadmin.php", {myid: id, pid: <?php echo $id;?>, token: '<?php echo encode($_SESSION['uid']);?>', operation: 1 });
	$('#makead'+id).fadeOut('fast');
}
function deleteadmin(id){
	$.post("makeadmin.php", {myid: id, pid: <?php echo $id;?>, token: '<?php echo encode($_SESSION['uid']);?>', operation: 2 });
	$('#delete'+id).fadeOut('fast');
	$('#name'+id).fadeOut('slow');
}
</script>


<?php 
	include (inner().'top/footer.php'); 
	mysql_close($mysql_link);
?>
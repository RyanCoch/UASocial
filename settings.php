<?php
/**
*	settings.php
*	Mike Gordo mgordo@live.com
*/
//header ("Connection: close");

$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

include ("top/header2.php");
?>

<script type="text/javascript">
function actpic() {
	var tp = "pic";
	$.post("utils/wpost.php", {type: tp, id: <?php echo $uid;?>});
}
</script>
    
<?php
	//extract all the data
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	$picture = $_SESSION['picture'];
	
	include_once("utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
?>

<div id="left" style="min-height:750px;">
<!-- empty field -->

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
				$newimage = getcwd()."/pictures/".$uid."/";
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
			  // echo "<p class=\"error\">".$error["image_file"].$error["imagename"].$error["directory"].$error["result"].$error["ext"]."</p>";
		   } else {
		
					$src =	$newimage;
					if (file_exists($src)){
						ini_set('memory_limit','64M');
						
						$new_size_x = 540;
						$new_size_y = 700;
						list($width,$height) = getimagesize($src);
						$image = imagecreatefromjpeg($src);
						if(!$image) {
							 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=settings.php">';
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
						imagejpeg($image_p,"pictures/".$uid."/".$gen_num."60.jpg");
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
						imagejpeg($image_p,"pictures/".$uid."/".$gen_num."30.jpg");
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
						imagejpeg($image_p,"pictures/".$uid."/".$gen_num."170.jpg");
						imagedestroy($image_p);
						imagedestroy($image);
					}
					
					$_SESSION['picture'] = "$uid/".$gen_num;
					$picture = "$uid/".$gen_num;
					$result = mysql_query("UPDATE profile SET picture = '$uid/$gen_num' WHERE uid = '$uid'", $mysql_link) or die(mysql_error());
		   } // else
		}	
	?>
	
<?php include("utils/leftmenu.php");?>

</div> <!-- left -->

<div id="right" style="min-height:750px;">
<div id="settings">
<h2>Settings</h2>


<?php
if ($update=="2") :	//continue
?>
					<div id="blue_frame" style="height:380px;">
                    <p><strong>Please review the new profile picture</strong></p><br /><center>
                    <img class="prof" style="max-width:520px;" src="pictures/<?php echo $picture;?>170.jpg" />
                    <br /><br />
                    <a class="fb" href="settings.php?update=3">Crop the picture</a></center>
                    <form action="settings.php" style="float:right;">                    
                    <button class="bsettings" onclick="javascript:actpic();">Save</button>
                    </form>                    
                    
                    <span style="clear:both;">&nbsp;</span><br />

                    </div>                            
                            
 <?
							
	echo "</div></div>";
	include ("footer.php");
	exit;	
	endif;	

if ($update=="3") :	//cropping the picture
?>
       
   		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
		
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
                    <img class="prof" src="<?php echo $urladdress."/pictures/".$picture.".jpg";?>" id="cropbox" />
                    </center>
                        <form method="post" action="settings.php?update=4"  onSubmit="return checkCoords();">
                        <input type="hidden" name="src" value="<?php echo "pictures/".$picture;?>" />
                        <input type="hidden" id="x" name="x" />
                        <input type="hidden" id="y" name="y" />
                        <input type="hidden" id="w" name="w" />
                        <input type="hidden" id="h" name="h" />
                        <button class="bsettings" value="Save" onclick="javascript:actpic();">Save</button>
                        </form>
                        <br />
 <?
	echo "</div></div>";
	include ("footer.php");
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

	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=settings.php">';							
	echo "</div></div>";
	include ("footer.php");
	exit;	
	endif;						
	
	
	if ($update=="1") {   
		//read the parameters
		$deactivate = $_POST['deactivate'];
		$name = addslashes(trim(strip_tags($_POST['name'])));
		$password1 = strip_tags($_POST['password_c1']);
		$password2 = strip_tags($_POST['password_c2']);
		$sex = $_POST['sex'];
		/////////////////////////////////////////////////
		$css = 2;//$_POST['css'];
		/////////////////////////////////////////////////
		$facebook = addslashes(strip_tags($_POST['facebook']));
		$gplus = addslashes(strip_tags($_POST['gplus']));
		$twitter = addslashes(strip_tags($_POST['twitter']));
		$chat_color = $_POST['chat_color'];
		$sorfer = addslashes(trim(strip_tags($row['sorfer'])));
		$headline = addslashes(trim(strip_tags($_POST['headline'])));
		$about = addslashes(trim(strip_tags($_POST['about'])));
		$dob_ = $_POST['dob'];
		$dob_flag = $_POST['dob_flag'];
		$pob = addslashes(trim(strip_tags($_POST['pob'])));
		$pob_flag = $_POST['pob_flag'];
		$major = addslashes(trim(strip_tags($_POST['major'])));
		$quad_ = $_POST['quad'];
		$quad_flag = $_POST['quad_flag'];
		$life_prior = $_POST['life_prior'];
		$foreign = addslashes(trim(strip_tags($_POST['foreign_lang'])));
		$body_type_ = $_POST['body_type'];
		$haircolor_ = $_POST['haircolor'];
		$tattoo_ = $_POST['tattoo'];
		$alcohol = addslashes(trim(strip_tags($_POST['alcohol'])));
		$status_ = $_POST['status'];
		$look_for_ = $_POST['look_for'];
		$look_age_from = $_POST['look_age_from'];
		$look_age_to = $_POST['look_age_to'];
		$look_what_ = addslashes(trim(strip_tags($_POST['look_what'])));
		
		if (!$quad_flag) $quad_flag = 0;
		if (!$dob_flag) $dob_flag = 0;
		if (!$pob_flag) $pob_flag = 0;		
		//check correctness
		$query = "UPDATE profile SET ";
		if($deactivate=="kill")	$query .= "active = -1, ";
		else $query .= "active = ".rand(100,5000).", ";
		
	    $string_name = "^[a-z .'-]+$";
		  if(!eregi($string_name,$name) || strlen($name)<2) {
			echo "<p class=\"error\">The <strong>Name</strong> you entered does not appear to be valid.</p>";
			//$update = "0";
		  } else {
			$query .= "name = '$name', ";
			$_SESSION['name'] = $name;
		  }
	
	    $query .= "css = '$css', ";
	    $query .= "headline = '$headline', ";

		$facebook = processCorrectLink($facebook,1);
		$gplus = processCorrectLink($gplus,2);
		$twitter = processCorrectLink($twitter,3);
		
	    $query .= "facebook = '$facebook', ";
	    $query .= "gplus = '$gplus', ";
	    $query .= "twitter = '$twitter', ";

	    $query .= "chat_color = '$chat_color', ";
	    $query .= "sorfer = '$sorfer', ";		
		$query .= "about = '$about', ";
		  
		if((strlen($password2)>0 && $password1!=$password2) || (strlen($password2)<6 && strlen($password2)>0)) {
			echo "<p class=\"error\">The <strong>Passwords</strong> you entered does not match or shorter than 6 symbols.</p>";
			//$update = "0";
		  } elseif(strlen($password1)>0 && strlen($password2)>0 )  {$password1 = md5($password1); $query .= "password = '$password1', ";}
		
		if ($sex==1 && $picture[0]=="0") $query .= "picture = '0/boy', ";
		if ($sex==2 && $picture[0]=="0") $query .= "picture = '0/girl', ";
		$query .= "sex = $sex, ";


		if (strpos($dob_, '/') == 2) {
			//data like this: mm/dd/yyyy
			$dob_ = substr($dob_,6,4)."-".substr($dob_,0,2)."-".substr($dob_,3,2);			
		}		
		if (strpos($dob_, '-') == 2) {
			//data like this: mm-dd-yyyy
			$dob_ = substr($dob_,6,4)."-".substr($dob_,0,5);			
		}
		$stamp = strtotime($dob_); 
  		if ((!is_numeric($stamp) || strlen($dob_)<10 || $dob_[4]!="-" ||  $dob_[7]!="-")) {
			echo "<p class=\"error\">The <strong>Date of birth</strong> you entered does not appear to be valid.</p>";
			$update = "0";
		}
		if ($dob_ == '0000-00-00') {
			$update = '1';
		} else {
			$month = date( 'm', $stamp ); 
			$day   = date( 'd', $stamp ); 
			$year  = date( 'Y', $stamp ); 
			if (!checkdate($month, $day, $year)) {
				echo "<p class=\"error\">The <strong>Date of birth</strong> you entered does not appear to be valid.</p>";
				//$update = "0";
			} else $query .= "dob = '$dob_', ";
		}
		$query .= "dob_flag = $dob_flag, ";
		$query .= "pob = '$pob', ";
		$query .= "pob_flag = $pob_flag, ";
		$query .= "major = '$major', ";
		$query .= "quad = $quad_, ";
		$query .= "quad_flag = $quad_flag, ";
		
		$life_prior_ = "";
		for ($i = 0; $i<sizeof($life_prior); $i++)
			$life_prior_ .= $life_prior[$i];

		$query .= "life_prior = '$life_prior_', ";
		$query .= "foreign_lang = '$foreign', ";
		$query .= "body = $body_type_, ";
		$query .= "haircolor = $haircolor_, ";
		$query .= "tattoo = $tattoo_, ";
		$query .= "alcohol = '$alcohol', ";
		$query .= "status = $status_, ";
		$query .= "look_for = $look_for_, ";

  		if (!is_numeric($look_age_from) || !is_numeric($look_age_to) || $look_age_from>$look_age_to) {
			echo "<p class=\"error\">The <strong>Fooking for/Age</strong> you entered does not appear to be valid.</p>";
			//$update = "0";
		} else {
		$query .= "look_age_from = $look_age_from, ";
		$query .= "look_age_to = $look_age_to, ";
		$query .= "look_what = '$look_what_' ";
		$_SESSION['looking_for'] = $look_for_;
		$_SESSION['looking_for_age_from'] = $look_age_from;
		$_SESSION['looking_for_age_to'] = $look_age_to;
		}
		$query .= "WHERE uid = '$uid'";
		
		if ($update=="1") {
			$result = mysql_query($query, $mysql_link);
			if (!$result) {
				echo "<p class=\"error\">Unable to save changes!</p>";			
			} else echo "<p class=\"error\">Changes have been saved.</p>";			
		}

		$_SESSION['css'] = $css;		
		$_SESSION['sex'] = $sex;
		$_SESSION['chat_color'] = $chat_color;

	} //save the parameters

	$query = "SELECT * FROM profile ";
	$query .= "WHERE uid='$uid'";
	$result = mysql_query($query, $mysql_link);
	$row = mysql_fetch_array($result);
	mysql_close($mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//something went very very wrong!
		 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=unknown">';
		 exit;
	 }
	 
	 include_once("utils/loading.php"); //loading profile data	 
	 $email = $row['email'];
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

    
    <h3>System</h3>

    <div id="blue_frame">
    	<div id="temp">
            <img src="<?php echo "pictures/".$picture."60.jpg";?>" width="60px" height="60px" class="prof"/>
            <button onclick="javascript:display_form();" style="float:right; margin:30px 260px 0 0;" class="bsettings">Update profile picture</button>
        </div>
        <form style="visibility:hidden; position:absolute; left:0; padding:0; margin:0;" id="profile_picture_form" method="POST" enctype="multipart/form-data" name="image_upload_form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label class="long"><strong>Please choose the profile picture</strong><br /><small>(Maximum size: 4Mb)</small></label><br />
            <input type="hidden" name="update" value="2"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
            <input type="file" style="width:400px;" id="image_file" name="image_file" onchange="javascript:changeFile();"><br />
            <button type="submit" id="upload_button" class="bsettings" value="Upload Image" name="action">Upload picture</button>
            <label id="fname" style="display:none;"></label>
        </form>
        
    </div>
    
    <form method="post" action="settings.php">    
    	<input type="hidden" name="update" value="1"/>
        <label>Profile address </label>
        <label class="bigger2"><small><?php echo $urladdress."/profile.php?id=".$uid; ?></small></label> <br/><br/>
        
<!--        <label>Color scheme </label>
        <select name="css" class="tsettings">
        <option value="2" <?php if ($css=='2') echo "selected";?>>Alternative</option>
        <option value="0" <?php if ($css=='0') echo "selected";?>>Colorful</option>
        </select><br />
    -->    
        <label>Email </label>
        <input class="tsettings" disabled="disabled" type="text" name="email" maxlength="50" size="30" value="<?php echo $email; ?>"/> <br/>

        <label>Password </label>
        <input class="tsettings" type="password" name="password_c1" id="pwd1" maxlength="50" size="30" value="" onKeyUp="javascript:checkpwd(1);"/> <br/>

        <label>Repeat password </label>
        <input class="tsettings" type="password" name="password_c2" id="pwd2" maxlength="50" size="30" value="" onKeyUp="javascript:checkpwd(2);"/>
        
        <label style="display:none;" class="error" id="match">Passwords don't match! </label><br/>
        
        
        
    <h3 class="personal">Personal</h3>
       
        <label>Display name </label>
        <input class="tsettings" type="text" name="name" size="30" maxlength="50" value="<?php echo $name; ?>"/> <br/>

        <label>Sex </label>
        <select name="sex" class="tsettings">
        <option value="0" <?php if ($sex==0) echo "selected";?>>N/A</option>
        <option value="1" <?php if ($sex==1) echo "selected";?>>Male</option>
        <option value="2" <?php if ($sex==2) echo "selected";?>>Female</option>
        </select><br />

        <label class="bigger3">Fraternities/Sororities:</label>
        <input class="tsettings" type="text" name="sorfer" size="25" maxlength="50" value="<?php echo $sorfer; ?>"/> <br/>

        <label class="bigger3">Facebook: <strong>www.facebook.com/</strong></label>
        <input class="tsettings" type="text" name="facebook" size="25" maxlength="100" title="Please input only your name. Don't input www.facebook.com/" value="<?php echo $facebook; ?>"/> <br/>

        <label class="bigger3">Twitter: <strong>www.twitter.com/#!/</strong></label>
        <input class="tsettings" type="text" name="twitter" size="25" maxlength="100" title="Please input only your name. Don't input www.twitter.com/#!/" value="<?php echo $twitter; ?>"/> <br/>

        <label class="bigger3">Google plus: <strong>plus.google.com/</strong></label>
        <input class="tsettings" type="text" name="gplus" size="25" maxlength="100" title="Please input only your name. Don't input plus.google.com/" value="<?php echo $gplus; ?>"/> <br/>

        <label class="bigger">Color of chat-messages: </label>
        <select name="chat_color" class="tsettings">
        <option class="sex1" value="1" <?php if ($chat_color==1) echo "selected";?>>Light-blue</option>
        <option class="sex2" value="2" <?php if ($chat_color==2) echo "selected";?>>Pink</option>
        <option class="sex3" value="3" <?php if ($chat_color==3) echo "selected";?>>Blue</option>
        <option class="sex4" value="4" <?php if ($chat_color==4) echo "selected";?>>Red</option>
        <option class="sex5" value="5" <?php if ($chat_color==5) echo "selected";?>>Green</option>
        <option class="sex6" value="6" <?php if ($chat_color==6) echo "selected";?>>Yellow</option>
        <option class="sex7" value="7" <?php if ($chat_color==7) echo "selected";?>>Cyan</option>
        <option class="sex8" value="8" <?php if ($chat_color==8) echo "selected";?>>Grey</option>
        </select><br />

        <label>Headline </label>
        <input class="tsettings" type="text" name="headline" size="30" maxlength="50" value="<?php echo $headline; ?>"/> <br/>

        <label id="atext" class="long">About</label><br />
		<textarea class="tsettings" name="about" id="about" maxlength="500" cols="60" rows="6" onkeyup="javascript:textarea(500, 'About ');"><?php echo strip_tags($about); ?></textarea><br />
		
		
        <label class="bigger">Date of birth <small>(YYYY-MM-DD)</small></label>
        <input class="tsettings" type="text" name="dob" size="20" maxlength="10" value="<?php echo $dob_; ?>"/>
        <input type="checkbox" class="styled" name="dob_flag" value="1" <?php if ($dob_flag==1) echo "checked";?> /> Display my age<br />

        <label class="bigger">Place of birth </label>
        <input class="tsettings" type="text" name="pob" size="20" maxlength="50" value="<?php echo $pob; ?>"/>
        <input type="checkbox" class="styled" name="pob_flag" value="1"  <?php if ($pob_flag==1) echo "checked";?> /> Display<br />
        
        <label>My major </label>
        <input class="tsettings" type="text" name="major" size="30" maxlength="50" value="<?php echo $major; ?>"/> <br/>
        
        <label>Residence </label>
        <select name="quad" class="tsettings">
        <option value="0" <?php if ($quad_==0) echo "selected";?>>No answer</option>
        <option value="1" <?php if ($quad_==1) echo "selected";?>>Colonial Quad</option>
        <option value="2" <?php if ($quad_==2) echo "selected";?>>State Quad</option>
        <option value="3" <?php if ($quad_==3) echo "selected";?>>Dutch Quad</option>
        <option value="4" <?php if ($quad_==4) echo "selected";?>>Indian Quad</option>
        <option value="5" <?php if ($quad_==5) echo "selected";?>>Alumni Quad</option>
        <option value="6" <?php if ($quad_==6) echo "selected";?>>Freedom Apartments</option>
        <option value="7" <?php if ($quad_==7) echo "selected";?>>Empire Commons</option>
        <option value="8" <?php if ($quad_==8) echo "selected";?>>Off Campus</option>
        </select>
        <input type="checkbox" name="quad_flag" class="styled" value="1" <?php if ($quad_flag==1) echo "checked";?> /> Display<br />
		<br />
        
		<label class="long">Life priorities <small>(Press Ctrl for multiple choice)</small> </label><br />
        <select class="tsettings" multiple="multiple" size="5" name="life_prior[]" style="width:300px;">
         <option value="1" <?php if (contain($life_prior_,"1")) echo "selected";?>>Career</option>
         <option value="2" <?php if (contain($life_prior_,"2")) echo "selected";?>>Finiancial Independence</option>
         <option value="3" <?php if (contain($life_prior_,"3")) echo "selected";?>>Family</option>
         <option value="4" <?php if (contain($life_prior_,"4")) echo "selected";?>>Peace of mind</option>
         <option value="5" <?php if (contain($life_prior_,"5")) echo "selected";?>>Sexual life</option>
         <option value="6" <?php if (contain($life_prior_,"6")) echo "selected";?>>Creativity</option>
         <option value="7" <?php if (contain($life_prior_,"7")) echo "selected";?>>Freedom</option>
        </select><br /><br />
        
        <label>Languages </label>
        <input class="tsettings" type="text" name="foreign_lang" size="30" maxlength="50" value="<?php echo $foreign; ?>"/> <br/>

        <label>Body type </label>
        <select name="body_type" class="tsettings">
        <option value="0" <?php if ($body_type_==0) echo "selected";?>>No answer</option>
        <option value="1" <?php if ($body_type_==1) echo "selected";?>>Slim</option>
        <option value="2" <?php if ($body_type_==2) echo "selected";?>>Average</option>
        <option value="3" <?php if ($body_type_==3) echo "selected";?>>Athletic</option>
        <option value="4" <?php if ($body_type_==4) echo "selected";?>>Muscular</option>
        <option value="5" <?php if ($body_type_==5) echo "selected";?>>Large</option>
        </select><br />

        <label>Haircolor </label>
        <select name="haircolor" class="tsettings">
        <option value="1" <?php if ($haircolor_==1) echo "selected";?>>Black</option>
        <option value="2" <?php if ($haircolor_==2) echo "selected";?>>Blond</option>
        <option value="3" <?php if ($haircolor_==3) echo "selected";?>>Brown</option>
        <option value="4" <?php if ($haircolor_==4) echo "selected";?>>Red</option>
        <option value="5" <?php if ($haircolor_==5) echo "selected";?>>Grey</option>
        <option value="6" <?php if ($haircolor_==6) echo "selected";?>>White</option>
        <option value="7" <?php if ($haircolor_==7) echo "selected";?>>Bald</option>
        <option value="8" <?php if ($haircolor_==8) echo "selected";?>>Mixed</option>
        <option value="9" <?php if ($haircolor_==9) echo "selected";?>>Shaved</option>
        </select><br />

        <label class="bigger">Tattoo/piercing </label>
        <select name="tattoo" class="tsettings">
        <option value="0" <?php if ($tattoo_==0) echo "selected";?>>None</option>
        <option value="1" <?php if ($tattoo_==1) echo "selected";?>>Tattoo</option>
        <option value="2" <?php if ($tattoo_==2) echo "selected";?>>Piercing</option>
        <option value="3" <?php if ($tattoo_==3) echo "selected";?>>Both</option>
        </select><br />

        <label class="bigger">Alcohol / cigarettes </label>
        <input class="tsettings" type="text" name="alcohol" size="30" maxlength="50" value="<?php echo $alcohol; ?>"/> <br/>

        <label class="bigger">Relationship status </label>
        <select name="status" class="tsettings">
        <option value="0" <?php if ($status_==0) echo "selected";?>>No answer</option>
        <option value="1" <?php if ($status_==1) echo "selected";?>>Single</option>
        <option value="2" <?php if ($status_==2) echo "selected";?>>In a Relationship</option>
        <option value="3" <?php if ($status_==3) echo "selected";?>>Engaged</option>
        <option value="4" <?php if ($status_==4) echo "selected";?>>Married</option>
        </select><br />
        <br />

        <label>Looking for </label>
        <select name="look_for" class="tsettings">
        <option value="1" <?php if ($look_for_==1) echo "selected";?>>Boys</option>
        <option value="2" <?php if ($look_for_==2) echo "selected";?>>Girls</option>
        <option value="3" <?php if ($look_for_==3) echo "selected";?>>Boys and girls</option>
        </select><br />

		<label>Ages </label>
        <input class="tsettings" type="text" name="look_age_from" size="7" maxlength="2" value="<?php echo $look_age_from; ?>"/>
        <label class="none">to</label>
        <input class="tsettings" type="text" name="look_age_to" size="7" maxlength="2" value="<?php echo $look_age_to; ?>"/> <br/>
        
        <label>For </label>
        <input class="tsettings" type="text" name="look_what" size="30" maxlength="50" value="<?php echo $look_what_; ?>"/> <br/>
        <label class="long" style="margin-left:130px;"><small>For example: Friendship, Relationship, Living together...</small></label><br />

		<br />
		<span style="background-color:<?php if($css=="1")echo"#ddd";elseif($css=="0") echo"#c7e3ff";elseif($css=="2") echo"#f4f4f4;border:1px solid #dcdcdc;"; ?>; display:block; padding:10px; height:60px; text-align:center;">
		<input type="checkbox" class="styled" name="deactivate" <?php if ($active<1) echo "checked";?> value="kill">Make my profile not searchable<br />
        <small>If you change your mind, profile can become searchable again.</small>
		</span>

        <br />
		<button type="submit" class="bsettings" id="button" value="Save settings">Save settings</button>
    </form>


 
</div> <!-- profile -->
</div> <!-- right -->
<?php
include ("footer.php");
?>
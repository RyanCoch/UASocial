<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

include("settings.php");
date_default_timezone_set("America/New_York");
$uid = $_SESSION['uid'];
$from_id=$_POST['from_id'];
$to_id=$_POST['to_id'];
$subject=addslashes(strip_tags($_POST['subject']));
$text = parse_links(addslashes(strip_tags($_POST['text'])));
$today = date("Y-m-d H:i:s");
function parse_links($text)
{
        $text = html_entity_decode($text);
        $text = " ".$text;
        $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
        '\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
        $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
        '<a href="mailto:\\1" target=_blank>\\1</a>', $text);
        return $text;
}
//uploading the picture
unset($imagename);
if(!isset($_FILES) && isset($HTTP_POST_FILES))
	$_FILES = $HTTP_POST_FILES;
if(!isset($_FILES['attach_file']))
	$error["attach_file"] = "An image was not found.";
	$imagename = basename($_FILES['attach_file']['name']);
	if (end(explode(".", strtolower($_FILES['attach_file']['name']))) != 'jpg')
		$error["ext"] = "Invalid file type. Only jpeg files allowed.";		
	if(empty($imagename))
		$error["imagename"] = "The name of the image was not found.";
	if(empty($error)) :
				$newimage = "../attach/";
				$gen_num = genRandomString()."-".genRandomString();
				$newimage .= $gen_num.".jpg";
				$result = @move_uploaded_file($_FILES['attach_file']['tmp_name'], $newimage);
				if(empty($result))
					$error["result"] = "There was an error moving the uploaded file.";
			if (!empty($error)) {
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../messages.php?to='.$_POST['to_id'].'&error=attach">';
				exit;
		   } else {
		
					$src =	$newimage;
					if (file_exists($src)){
						ini_set('memory_limit','64M');						
						$new_size_x = 540;
						$new_size_y = 700;
						list($width,$height) = getimagesize($src);
						$image = imagecreatefromjpeg($src);
						if(!$image) {
							echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../messages.php?to='.$_POST['to_id'].'&error=attach">';
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
							imagedestroy($image);	
						}
						$left_margin = (540 - $width) / 2 - 20;
						$text .= ((strlen($text)>0)?"<br /><br />":"")."<center><img src=\"attach/$gen_num.jpg\" /></center>";
						$subject = "<img src=\"images/attach.png\" width=\"12\" height=\"12\" />".$subject;
					}
		   }

endif;

if(strlen(strip_tags($subject)) < 1) {
	 $subject .= " No Subject";
	 }
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

  	$query="INSERT INTO message (mid, from_uid, to_uid, subject, text, type, datetime) VALUES  ('0','$uid','$to_id','$subject','$text', '1','$today')";
 	$result = mysql_query($query, $mysql_link);
	mysql_close($mysql_link);
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../messages.php">';
?>
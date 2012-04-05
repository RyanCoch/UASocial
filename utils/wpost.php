<?php
$a = session_id();
if(empty($a)) session_start();
// POST TO THE WALL
// Database wall
// wid : integer
// uid : integer // 0 means BROADCAST
// line : text
// timestamp : timestamp

include_once('settings.php');
include_once('functions.php');

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

date_default_timezone_set("America/New_York");

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

function imageExists($image) {
	$handle = @fopen($image,'r');
	if($handle) {
	   fclose($handle);
	   return true;
	} else
	   return false;
}

function normvideo($v) {
	$l = '';
	$flag = false;
	for ($i = 1; $i<strlen($v); $i++){
		if($flag && $v[$i]=='&') $flag = false;
		if(!$flag && (($v[$i-2].$v[$i-1])=='v=' || ($v[$i-2].$v[$i-1])=='V=')) $flag = true;
		if($flag) $l.=$v[$i];
	}
	return "http://www.youtube.com/v/".$l;
}

function addLine($i, $line, $broadcast, $tpe) {	// broadcast = 1, tpe = 'u' or 'p'
	global $mysql_link;
	$now = time();
	$author = $tpe.$i;
	$result = mysql_query("INSERT INTO `wall`(`uid`,`author`,`line`,`timestamp`,`broadcast`) VALUES ('$i', '$author', '$line', '$now', '$broadcast') ", $mysql_link );
	mysql_close($mysql_link);
}


$type = $_POST['type']; // TYPE OF THE POST

if ($type == 'user') { //new user on the Network
	$id = $_POST['id'];
	if (!$id) exit;

	//get user information
	$result = mysql_query("SELECT name, uid FROM `profile` WHERE `uid` = '$id' ", $mysql_link );
	$r = mysql_fetch_array($result); 
	
	$line = "<div class=\"wall_user\">";
	$line .= "<a href=\"profile.php?id=".$id."\">".$r['name']."</a> joined UASocial.";
	addLine($id, $line, '1', 'u');
	exit;	
}

if ($type == 'page') { //new page on the Network
	$id = $_POST['id'];
	if (!$id) exit;

	//get user information
	$result = mysql_query("SELECT `name`, `pid` FROM `page` WHERE `pid` = '$id' ", $mysql_link );
	$r = mysql_fetch_array($result); 
	
	$line = "<div class=\"wall_user\">";
	$line .= "New group page <a href=\"/page/?id=".$id."\">".$r['name']."</a> joined UASocial.";
	addLine($id, $line, '1', 'p');
	exit;	
}

if ($type == 'pic') { //user changed his profile picture
	$id = $_POST['id'];
	if (!$id) exit;

	//get user information

	$result = mysql_query("SELECT name, uid, picture, sex FROM `profile` WHERE `uid` = '$id' ", $mysql_link );
	$r = mysql_fetch_array($result);

	$line = "<div class=\"wall_pic\">";
	$line .= "<a href=\"profile.php?id=".$id."\">".$r['name']."</a> updated ".(($r['sex']==1)?"his":"her")." profile picture.<br />";
	$line .= "<a href=\"profile.php?id=".$id."\"><img src=\"pictures/".$r['picture']."60.jpg\" width=\"60px\" height=\"60px\" /></a>";
	addLine($id, $line,'1','u');
	exit;	
}

if ($type == 'post') { // user posted something on the wall

//SECURITY CHECK
if (encode($_SESSION['uid'])!=$_POST['token']) {
	if ($official)
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../page/?id='.$id.'&error=attach">';
	else
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../wall.php?error=attach">';
	exit;
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
				$newimage = "../wall/attach/";
				$gen_num = "w".genRandomString()."-".genRandomString();
				$newimage .= $gen_num.".jpg";
				$result = @move_uploaded_file($_FILES['attach_file']['tmp_name'], $newimage);
				if(!$result)
					$error["result"] = "There was an error moving the uploaded file.";
			if (!empty($error)) {
				if ($official)
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../page/?id='.$id.'&error=attach">';
				else
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../wall.php?error=attach">';
				exit;
		   } else {
		
					$src =	$newimage;
					if (file_exists($src)){
						ini_set('memory_limit','64M');						
						$new_size_x = 500;
						$new_size_y = 700;
						list($width,$height) = getimagesize($src);
						$image = imagecreatefromjpeg($src);
						if(!$image) {
							if ($official)
								echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../page/?id='.$id.'&error=attach">';
							else
								echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../wall.php?error=attach">';
							exit;
							}			
						if($width>$new_size_x || $height>$new_size_y) {	// do we actually need to make the file smaller
							$koef = ($width/$height);
							if ($width >= $height) { 		//horizontal
								$new_size_x = 500;
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
						$imagename = "../wall/attach/$gen_num.jpg";
					}
		   }
endif;

	$id = $_POST['id'];					// id of page OR user
	$usr = $_POST['usr'];				// id of USER posting on the Page (being not admin)
	$cat = $_POST['cat']; 				// category
	$official = $_POST['official']; 	// means POST FROM PAGE
	$broadcast = $_POST['broadcast']; 	// means broadcast
	if(!$broadcast) $broadcast = '0';

	$text = parse_links(trim(strip_tags($_POST['text']))); 	// text
	$img = trim(strip_tags($_POST['img']));	//attached image
	if (!$img && $imagename) $img = $imagename;
	$video = strip_tags($_POST['video']);	//attached video

	if (!$id || (strlen($text) < 2 && !$video && !$img)) {
		if ($official)
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../page/?id='.$id.'">';
		else
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../wall.php">';
		exit;
	}

	//get user information
	if ($official) {
		$result = mysql_query("SELECT `name`, `pid` FROM `page` WHERE `pid` = '$id' ", $mysql_link );
		if ($usr)
			$result_u = mysql_query("SELECT `name`, `uid` FROM `profile` WHERE `uid` = '$usr' ", $mysql_link );
	}
	else
		$result = mysql_query("SELECT `name`, `uid` FROM `profile` WHERE `uid` = '$id' ", $mysql_link );
	$r = mysql_fetch_array($result);
	if ($usr) $r_u = mysql_fetch_array($result_u);

	$line = "<div class=\"wall_post\">";
	if ($official && !$usr)
		$line .= "<a href=\"/page/?id=".$id."\"><strong>".$r['name']."</strong></a><br />";
	elseif (!$official)
		$line .= "<a href=\"/profile.php?id=".$id."\"><strong>".$r['name']."</strong></a><br />";
	elseif ($official && $usr)	
		$line .= "<a href=\"/profile.php?id=".$usr."\"><strong>".$r_u['name']."</strong></a> on <a href=\"/page/?id=".$id."\"><strong>".$r['name']."</strong></a> <br />";
	
	$line .= $text;
	
	if ($img && imageExists($img)) {
		$imgid = "i".genRandomString().rand(100,500);
		list($width,$height) = getimagesize($img);	
		if ($width>500) {
			$koef = $height/$width;
			$width = 500;
			$height = $width * $koef; 
		}
		$line .= "<br />";
		$line .= "<img src=\"$img\" id=\"$imgid\" width=\"120px\" onclick=\"javascript:showPic(\'$imgid\',$width,$height);\" style=\"cursor:nw-resize;\" >";
	}	

	if ($video) {
		$video = normvideo($video);
		$line .= "<br />";
		$line .= "<object width=\"500\" height=\"284\" style=\"z-index:15;\"><param name=\"movie\" value=\"$video\"></param><param name=\"allowFullScreen\" value=\"true\"></param>";
		$line .= "<param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"$video\" type=\"application/x-shockwave-flash\" width=\"500\" height=\"284\" allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed></object>";
	}	
	
	$line = addslashes($line);
	
	if ($official) {
		addLine($id, $line, $broadcast, 'p');
	} else
		addLine($id, $line, $broadcast, 'u');

	if ($official)
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../page/?id='.$id.'">';
	else
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../wall.php">';
		exit;	
}

?>
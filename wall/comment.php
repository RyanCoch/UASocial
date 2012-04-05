<?php
$a = session_id();
if(empty($a)) session_start();


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


$id = $_POST['id'];						// page id
$operation = $_POST['operation'];		// 0 - add comment or 1 - delete comment
$wid = $_POST['wid'];					// post id
$cid = $_POST['cid'];					// comment id
$token = $_POST['token'];
$uid = $_SESSION['uid'];
if (!$operation && !$_POST['comment']) exit;
$comment = trim(parse_links(addslashes(strip_tags($_POST['comment']))));

include_once('../utils/functions.php');


//SECURITY CHECK
if (encode($_SESSION['uid'])!=$_POST['token'])
	exit;

include_once('../utils/settings.php');

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

if ($operation == 0) {	// add
	if (!$comment || !$wid || !$uid) exit;
	$today = time();
	$query = "INSERT INTO `wall_com`(`wid`, `uid`, `type`, `comment`, `datetime`) VALUES ('$wid', '$uid', '1', '$comment', '$today' ) ";	
}
if ($operation == 1) {	// delete
	if (!$cid) exit;
	$today = time();
	$query = "DELETE FROM `wall_com` WHERE `cid` = '$cid' ";	
}
$result = mysql_query($query, $mysql_link );

mysql_close($mysql_link);

?>
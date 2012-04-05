<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}

$start = $_GET['start'];
$pageid = $_GET['pageid'];
$imadmin = $_GET['admin'];

if (!$pageid || !$start) exit;

include_once("../utils/settings.php");

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

$author = 'p'.$id;

$get_wall = mysql_query("SELECT * FROM `wall` WHERE `author` = '$author' ORDER BY `wid` DESC LIMIT 16, $start", $mysql_link);	//get 50 records from wall table
while($rowpp = mysql_fetch_array($get_wall)) {
	$postid = $row['wid'];
	echo nl2br($rowpp['line']);
	
	if ($imadmin) {
		// Delete link	
		echo "<a class=\"delPost\" id=\"post".$rowpp['wid']."\" href=\"javascript:deletePost(".$rowpp['wid'].")\">";
		echo "delete";
		echo "</a>\r\n";
	}

	echo "<br>";
	$time_online = 0;
	$last_time = time() - $rowpp['timestamp'];
	if ($last_time <= 0) $time_online = "Few seconds ago";
	if (!$time_online && $last_time<80) $time_online="Minute ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && $last_time<80) $time_online = "Hour ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "Yesterday";
	if (!$time_online) $last_time = round($last_time/24);
	if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online) $time_online = date("h:i a, M-j",$rowpp['timestamp']);
	echo "<span class=\"tstamp\">$time_online</span>&nbsp;&nbsp;&nbsp;";
	
	$iscomment = isComment($postid);
	if ($iscomment)
		echo "<a style=\"font-size:.9em;\" href=\"javascript:showcomments_load($postid);\"><strong>$iscomment comment".(($iscomment%10==1)?"":"s")."</strong></a><br>";
	else
		echo "<a style=\"font-size:.9em;\" href=\"javascript:showcomments($postid);\">write first comment</a><br>";
	?>
    <div id="comm<?php echo $postid;?>" style="display:none; margin-left:10px; border-left:1px solid #ff9900;">
        <input type="text" id="txt1-<?php echo $postid;?>" style="width:350px;margin-left:10px; " maxlength="255" onkeydown="return kd(event,<?php echo $postid;?>)" />
        <label id="txt2-<?php echo $postid;?>" style="margin-left:10px;"> </label>
        <?php 
		if ($iscomment) {
			echo "<br />\r\n";
			echo "<div id=\"loader".$postid."\" style=\"margin-left:10px; \"></div>";			
		} ?>
    </div>
    <?php
	echo "</div>\r\n";
}

function isComment($wwid) {
	global $mysql_link;
	$enum = mysql_query("SELECT COUNT(`cid`) FROM `wall_com` WHERE `wid` = '$wwid' ", $mysql_link);
	$enum = mysql_fetch_row($enum);
	if ($enum[0]) return $enum[0];
		else return 0;		
}

mysql_close($mysql_link);

?> 

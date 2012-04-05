<?php
$a = session_id();
if(empty($a)) session_start();
include ("top/header.php");
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

?>

<div id="left" style="min-height:750px;">

<?php include("utils/leftmenu.php");?>

</div> <!-- left -->

<script type="text/javascript">
var vis = 0;
function showPost() {
	if (!vis) {
		$('#postWall').fadeIn("slow");
		vis = !vis;
	} else {
		$('#postWall').fadeOut("fast", function() {
		vis = !vis;
		document.getElementById('attachP').style.display = "inline";
		document.getElementById('uplLabel').style.display = "none";
		document.getElementById('uplLabel2').style.display = "none";
		document.getElementById('attach_input').style.display = "none";
		document.getElementById('imgLabel').style.display = "none";
		document.getElementById('img').style.display = "none";
		document.getElementById('you').style.display = "none";
		document.getElementById('youLabel').style.display = "none";
		document.getElementById('youLabel2').style.display = "none";		
	});
	}
}
function showPic(id, width, height) {		
	document.getElementById(id).style.width = width+"px";	
	document.getElementById(id).style.height = height+"px";
}
function attach(v) {
$('#attachP').fadeOut('fast',function() {
if (v==1) {
	$('#uplLabel').fadeIn("fast");
	$('#uplLabel2').fadeIn("fast");
	$('#attach_input').fadeIn("fast");
}
if (v==2) {
	$('#img').fadeIn("fast");
	$('#imgLabel').fadeIn("fast");
}
if (v==3) {
	$('#you').fadeIn("fast");
	$('#youLabel').fadeIn("fast");
	$('#youLabel2').fadeIn("fast");
}

});
}
</script>

<div id="right" style="min-height:750px;">

<div id="profile">

<h2>Social Wall <a href="javascript:showPost();"><img src="images/alt/post.png" border="0px" title="Post on the wall" /></a></h2>
<div id="postWall" style="display:none; border-bottom:1px solid #dcdcdc; margin-bottom:10px;">
	<form method="post" enctype="multipart/form-data" id="post_form" action="utils/wpost.php">
	<input type="hidden" name="type" id="type" value="post" />
	<input type="hidden" name="cat" id="cat" value="1" />
	<input type="hidden" name="official" id="official" value="0" />
	<input type="hidden" name="broadcast" id="broadcast" value="1" />
	<input type="hidden" name="token" value="<?php echo encode($uid); ?>" />
	<input type="hidden" name="id" id="id" value="<?php echo $uid;?>" />
	<textarea class="tpic" name="text" id="text" maxlength="2000" style="width:460px;" rows="5"></textarea>
	<button class="bpicture" type="submit" id="submitmsg" style="position:relative; top:-13px; margin-left:10px;">Post</button>

    <p id="attachP" style="font-size:0.9em;">Attach&nbsp;&nbsp;&nbsp; <a class="wall_link" href="javascript:attach(1);" style="display:inline-block; width:100px;">Upload image</a> <a class="wall_link" href="javascript:attach(2);" style="display:inline-block; width:100px;">Link image</a> <a class="wall_link" href="javascript:attach(3);" style="display:inline-block; width:100px;">Youtube video</a></p>
    <label style="width:80px;display:none;" id="imgLabel">URL: </label><input type="text" name="img" id="img" style="width:340px; display:none;" value=""/>
    <label style="width:90px;display:none;" id="youLabel">YouTube link: </label><input type="text" name="video" id="you" style="width:340px; display:none;" value=""/>
    <label style="width:400px;margin-left:90px;display:none;color:#666;font-size:.8em;" id="youLabel2">Link format: http://www.youtube.com/watch?v=XXXXXXX</label>
    <label style="width:100px;display:none;" id="uplLabel">Choose picture: </label>
    <input type="file" id="attach_input" name="attach_file" style="width:340px; display:none;" />
    <label style="width:400px;margin-left:100px;display:none;color:#666;font-size:.8em;" id="uplLabel2">Only JPG pictures are supported.</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="4000000">
</div>
<?php

// get set of contacts
$get_contacts = mysql_query("SELECT `user2` FROM `friends` WHERE `type` = '1' AND `user1` = '$uid' ", $mysql_link);
if ($get_contacts) {	
	$flag = 0;
	while($row = mysql_fetch_array($get_contacts)) {
		if ($flag) $cont .= ",";
			else {
				$cont = " OR `author` IN (";
				$flag = 1;
			}
		$cont .= "'p".$row['user2']."'";
	}
	$cont .= ") ";
}

if (!$flag) $cont = "";
$get_wall = mysql_query("SELECT * FROM `wall` WHERE `broadcast`='1' $cont ORDER BY `wid` DESC LIMIT 0, 15", $mysql_link);	//get 50 records from wall table
$cnt = rand(1,10);
while($row = mysql_fetch_array($get_wall)) {
	echo $row['line'];
	echo "<br>";
	$time_online = 0;
	$cnt--;
	$last_time = time() - $row['timestamp'];
	if ($last_time <= 0) $time_online = "few seconds ago";
	if (!$time_online && $last_time<80) $time_online="a minute ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && $last_time<80) $time_online = "about an hour ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "yesterday";
	if (!$time_online) $last_time = round($last_time/24);
	if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online) $time_online = date("h:i a, M-j",$row['timestamp']);
	echo "<span class=\"tstamp\">$time_online</span>&nbsp;&nbsp;&nbsp;&nbsp;";
	
if ($row['author'][0]=='p') {
	$postid = $row['wid'];
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
} // row[author][0]=='p'
	
	
	echo "</div>";
	if ($cnt==0)
		include (inner()."page/pagead.php");
}




function isComment($wwid) {
	global $mysql_link;
	$enum = mysql_query("SELECT COUNT(`cid`) FROM `wall_com` WHERE `wid` = '$wwid' ", $mysql_link);
	$enum = mysql_fetch_row($enum);
	if ($enum[0]) return $enum[0];
		else return 0;		
}

?>
<div id="wlog">

</div>
<a href="javascript:loadLog();" class="wall_link">Show More</a>

<script type="text/javascript">
var start = 0;
function loadLog() {		
		start += 15;
		$.ajax({
			url: "wload.php?start="+start,
			cache: false,
			success: function(html){	
			$("#wlog").html(html);	
		  	},
		});
}

function kd(e, wid)
{
    var intKey = (window.Event) ? e.which : e.keyCode;
    if (intKey == 13) { //enter key
        leavecomment(wid);
		return false;
    }
    return true;
}

function showcomments(wid) {
	$('#comm'+wid).fadeIn("fast");
}

function showcomments_load(wid) {
	$('#comm'+wid).fadeIn("fast");
		$.ajax({
			url: "/wall/loadcomment.php?wid="+wid,
			cache: false,
			success: function(html){	
			$('#loader'+wid).html(html);	
		  	},
		});	
}

function addslashes(str) {
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}

function leavecomment(wid) {
	cmt = addslashes(document.getElementById('txt1-'+wid).value);
	$.post("/wall/comment.php", { wid: wid, operation: 0, token: '<?php echo encode($uid); ?>', comment: cmt});
	$('#txt1-'+wid).fadeOut("fast");
	$('#txt2-'+wid).fadeOut("fast");
	document.getElementById('txt2-'+wid).innerHTML = "Comment saved.";
	$('#txt2-'+wid).fadeIn("fast");	
}

</script>

</div> <!-- profile -->
</div> <!-- right -->

<?php
include ("footer.php");
mysql_close($mysql_link);
?>

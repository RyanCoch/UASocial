<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
unset($a);
	
	include('../top/header.php'); 

	//extract all the data
	$id = $_GET['id'];
	$uid = $_SESSION['uid'];
	
	if ((!$id) || ($id == 0)) {	//id of page is not set
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php">';
		exit;		
	}
	
	include_once("../utils/functions.php");
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	$get_pictures = mysql_query("SELECT COUNT(`pic_id`) FROM `pagepicture` WHERE `pid` = '$id' ", $mysql_link);		//number of pictures
	$row = mysql_fetch_row($get_pictures); 
	$num_pictures = $row[0];
	if (!$get_pictures || $num_pictures==0) {
		//disregard
	 } else {
			if ($num_pictures>4) {
				$startpic = $num_pictures - 4;
			} elseif ($num_pictures>0)
				$startpic = 0;
			$get_pictures = mysql_query("SELECT `pic_id`, `picture` FROM `pagepicture` WHERE `pid` = '$id' LIMIT $startpic, 4", $mysql_link);		//get 4 pictures
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

	include_once("../utils/online.php");		// UPDATE MY ONLINE STATUS

	$rz = mysql_query("SELECT * FROM `page` WHERE `pid` = '$id'", $mysql_link);
	$rowp = mysql_fetch_array($rz);
	//mysql_close($mysql_link);
	
	if (empty($rowp)) {
		// no  such page
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php">';
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
	unset($pname, $ppicture);
	$contact_id = getFirstAdmin($rowp['admins']);
	$imadmin = isAdmin($rowp['admins'],$uid);	// checks if i'm admin of this page
	$isright = $rowp['right'];
	
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

<div id="chatonline" style="width:170px; margin-top:20px;">
<a href="/page/NewPage.php">Create a Page</a><br />
<a href="/report.php?uid=<?php echo $uid; ?>&pid=<?php echo $id;?>">report/block
</a>
</div>

</div> <!-- left -->


<div id="right" style="min-height:750px;">
<div id="profile">
<?php
	$headline = $rowp['headline'];
	$about = nl2br($rowp['about']);
	$category = $rowp['category'];	
?>
 
<h2 style="width:500px;"><?php echo $headline; ?><span class="category"><?php echo $category; ?> </span></h2>

<?php	
	if ($num>0):
		echo "<div id=\"picturebar\">\r\n";
		for ($i = 0; $i < $num; $i++){
			echo "<a href=\"pagepictures.php?id=$id&display=$tumb_i[$i]\">";
			echo "<img src=\"$tumb_p[$i]_sml.jpg\" border=\"1px\" />";
			echo "</a>\r\n";
		}
		echo "</div>\r\n\r\n";
	endif;
?>
 
<h3 class="about"><?php echo $about; ?></h3>

<h2>Social Wall 
<?php if ($imadmin || $isright) :?>
	<a href="javascript:showPost();"><img src="../images/alt/post.png" border="0px" title="Post on the wall" /></a>
<?php endif; ?>
</h2>
<?php if ($imadmin || $isright) :?>
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

<div id="postWall" style="display:none; border-bottom:1px solid #dcdcdc; margin-bottom:10px;">
	<form method="post" enctype="multipart/form-data" id="post_form" action="../utils/wpost.php">
	<input type="hidden" name="type" id="type" value="post" />
	<input type="hidden" name="cat" id="cat" value="1" />
	<input type="hidden" name="token" value="<?php echo encode($uid); ?>" />
	<input type="hidden" name="official" id="official" value="1" />
    <?php if ($imadmin) :?>
	<input type="checkbox" name="broadcast" id="broadcast" value="1" /> Broadcast
    <?php endif;?>
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>" /> <!-- here is page id -->
    <?php if (!$imadmin && $isright) :?>
	<input type="hidden" name="usr" value="<?php echo $uid;?>" /> <!-- here is user id -->
	<?php endif;?>
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
<?php endif; ?>

<?php

$author = 'p'.$id;
$number_of_posts = 0;

$get_wall = mysql_query("SELECT * FROM `wall` WHERE `author` = '$author' ORDER BY `wid` DESC LIMIT 0, 15", $mysql_link);	//get 15 records from wall table
while($row = mysql_fetch_array($get_wall)) {
	$number_of_posts++;
	$postid = $row['wid'];
	echo nl2br($row['line']);
	
	if ($imadmin) {
		// Delete link	
		echo "<a class=\"delPost\" id=\"post".$row['wid']."\" href=\"javascript:deletePost(".$row['wid'].")\">";
		echo "delete";
		echo "</a>\r\n";
		echo "<span class=\"delPost\" id=\"postd".$row['wid']."\"></span>";
	}
	
	echo "<br>";
	$time_online = 0;
	$last_time = time() - $row['timestamp'];
	if ($last_time <= 0) $time_online = "few seconds ago";
	if (!$time_online && $last_time<80) $time_online="minute ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && $last_time<80) $time_online = "about an hour ago";
	if (!$time_online) $last_time = round($last_time/60);
	if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "yesterday";
	if (!$time_online) $last_time = round($last_time/24);
	if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
	if (!$time_online) $time_online = date("h:i a, M-j",$row['timestamp']);
	echo "<span class=\"tstamp\">$time_online</span>&nbsp;&nbsp;&nbsp;";
	
	$iscomment = isComment($postid);
	if ($iscomment)
		echo "<a style=\"font-size:.9em;\" href=\"javascript:showcomments_load($postid);\"><strong>$iscomment comment".(($iscomment%10==1)?"":"s")."</strong></a><br>";
	else
		echo "<a style=\"font-size:.9em;\" href=\"javascript:showcomments($postid);\">write first comment</a><br>";
	?>
    <div id="comm<?php echo $postid;?>" style="display:none; margin-left:10px; border-left:1px solid #ff9900;">
        <input type="text" id="txt1-<?php echo $postid;?>" style="width:350px;margin-left:10px;" maxlength="255" onkeydown="return kd(event,<?php echo $postid;?>)" />
        <label id="txt1-<?php echo $postid;?>-2" style="margin-left:10px;"> </label>
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
?>



<div id="wlog" <?php if (!$number_of_posts) echo"style=\"min-height:520px;\"";?>>
</div>

<a href="javascript:loadLog();" class="wall_link">Show More</a>

<script type="text/javascript">
var start = 0;
function loadLog() {		
		start += 15;
		$.ajax({
			url: "pagewload.php?pageid=<?php echo $id;?>&start="+start+"&admin="+<?php echo $imadmin;?>,
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

function deletePost(w) {
	$.post("/wall/delete.php", {id: <?php echo $id;?>, wid: w, official: 1, token: '<?php echo encode($uid); ?>'});
	document.getElementById('post'+w).innerHTML = "";
	document.getElementById('postd'+w).innerHTML = "deleted";
}

function showcomments(wid) {
	$('#comm'+wid).fadeIn("fast");
}

function showcomments_load(wid) {
	$('#comm'+wid).fadeIn("fast");
		$.ajax({
			url: "/wall/loadcomment.php?wid="+wid+"&admin="+<?php echo $imadmin;?>,
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
	$.post("/wall/comment.php", {id: <?php echo $id;?>, wid: wid, operation: 0, token: '<?php echo encode($uid); ?>', comment: cmt});
	$('#txt1-'+wid).fadeOut("fast");
	$('#txt1-'+wid+'-2').fadeOut("fast");
	document.getElementById('txt1-'+wid+'-2').innerHTML = "Comment saved.";
	$('#txt1-'+wid+'-2').fadeIn("fast");	
}

function deletecomment(cid) {
	$.post("/wall/comment.php", {id: <?php echo $id;?>, cid: cid, operation: 1, token: '<?php echo encode($uid); ?>'});
	$('#walCom'+cid).fadeOut("fast");	
}


</script> 

</div> <!-- profile -->
</div> <!-- right -->


<?php 
include ('../top/footer.php'); 
mysql_close($mysql_link);
?>
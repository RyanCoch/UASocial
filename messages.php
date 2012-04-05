<?php
/* Messages.php Created and Monitored By Ryan Cochrane/Mike Gordo */
$a = session_id();
if(empty($a)) session_start();
include ("top/header2.php");
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

	
	$to = $_GET['to'];	//if we are trying to send a message. if this parameter is null, we are just working with messagebox
	$error = $_GET['error'];
	$uid = $_SESSION['uid'];			//my info
	$name = $_SESSION['name'];			//
	$picture = $_SESSION['picture'];	//
	$search_page = $_GET['page'];
	$subject = strip_tags($_POST['subject']);
	$errordel = $_GET['d'];
	$pid = $_GET['p'];
	$text = strip_tags($_POST['text']);
	$value = $_POST['value'];
	$sent = $_GET['s'];
	if (!$search_page) $search_page="0";
	
	include_once("utils/functions.php");	
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	
	include_once("utils/online.php");		// UPDATE ONLINE STATUS

?>
<script type="text/javascript">

function selectToggle(toggle, form) {
     var myForm = document.forms[form];
     for( var i=0; i < myForm.length; i++ ) { 
          if(toggle) {
               myForm.elements[i].checked = "checked";
          } 
          else {
               myForm.elements[i].checked = "";
          }
     }
}

function magic(s) {
		if (document.getElementById('switch').checked == false) {
			selectToggle(false, s);
		} else {
			selectToggle(true, s);
		}
	}

function ismaxlength(obj){
	var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
	if (obj.getAttribute && obj.value.length>mlength)
	obj.value=obj.value.substring(0,mlength)
}	

</script>

    <div id="left" style="min-height:750px;">
        <!-- empty field -->

        <?php
			if (!$to || $to == $uid){	//my profile
			
				include("utils/leftmenu.php");
			} else {
				include_once("utils/checkonline.php");	// GET ONLINE STATUS
				$get_block = mysql_query("SELECT COUNT(user1) FROM block WHERE (user1='$uid' AND user2='$to')OR(user1='$to' AND user2='$uid') ", $mysql_link);	//are threr any blocks?
	     	 	$row = mysql_fetch_row($get_block); 
	    	 	$num_block = $row[0];
				if ($num_block > 0) {
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php">';
					exit;
				 }	
				$query = "SELECT name, picture FROM profile WHERE uid='$to'";
				$result = mysql_query($query, $mysql_link);
				$row = mysql_fetch_array($result);
				if ( (!$result) || (!mysql_num_rows($result)) ) {
					// no  such user
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=picture.php">';
					exit;		
				 }

				$uname = $row['name'];
				$upicture = $row['picture'];
				$css = $_SESSION['css'];
			?>
            <script type="text/javascript">
			document.title = "<?php echo $uname; ?>";
			</script>
            	<h2 class="name"><?php echo $uname; ?><?php if($to && $to != $uid) echo $online; ?></h2>
       <?php 
			//See if added to contacts already
			$get_contact = mysql_query("SELECT * FROM friends WHERE (user1='$uid' AND user2='$to' AND type='0')", $mysql_link);	//is he in your contacts
			$row3 = mysql_fetch_row($get_contact); 
			$contact = $row3[0];
			//They are Contacts
			if ($contact > 0) {
				echo '<p class="incontact">In your contacts</p>';
			}else{ //Display add Contacts 
				echo '<a href="javascript: addToContacts()" id = "contact_link">Add to Contacts</a>';
				echo '<p class="incontact" id="incontact" style="display:none;">In your contacts</p>';
			}
				?>
                <a href="profile.php?id=<?php echo $to; ?>">      
				<img src="<?php echo "pictures/".$upicture."170.jpg"; ?>" alt="My Face" width="170" height="220" class="profile_picture" />
                </a>

                <ul class="leftmenu">
                    <li class="one"><a href="profile.php?id=<?php echo $to; ?>"><img src="images/<?php if($css=='2') echo "alt/"; ?>profile_icon.png" class="icon">Profile</a></li>
                    <li class="three"><a href="messages.php?to=<?php echo $to; ?>"><img src="images/<?php if($css=='2') echo "alt/"; ?>message_icon.png" class="icon">Send message</a></li>
                    <li class="two"><a href="smile.php?to=<?php echo $to; ?>"><img src="images/<?php if($css=='2') echo "alt/"; ?>smile_icon.png" class="icon">Send smile</a></li>
                    <li class="four"><a href="pictures.php?id=<?php echo $to; ?>"><img src="images/<?php if($css=='2') echo "alt/"; ?>picture_icon.png" class="icon">Pictures</a></li>
                </ul>

		<?php
			
			unset($uname, $upicture);
			}
		?>

    
    </div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
	<h2>Messages</h2>
    <?php
		if ($error == "unknown") {
			echo "<p class=\"error\">Can't send message at this time.<br />";
			echo "Please check your input and try again later.</p>";
			}

	if ($to) :
	$error = $_GET['error'];
	if ($error == 'attach') {
		echo "<p class=\"error\">Can't send message at this time.<br />";
		echo "Attachment error.</p>";
		}
	if($error == 'subject'){
		echo "<p class=\"error\">The Subject you entered do not appear to be valid.</p>";
		}
	if($error == 'message'){
		echo "<p class=\"error\">The Message body should not be empty.</p>";
		}
	//if we are trying to send message to someone
	$query = "SELECT name FROM profile WHERE uid='$to'";
	$result = mysql_query($query, $mysql_link);
	$row = mysql_fetch_array($result);
	$query2 = "SELECT name FROM profile WHERE uid='$uid'";
	$result2 = mysql_query($query2, $mysql_link);
	$row2 = mysql_fetch_array($result2);
	mysql_close($mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		// no  such user
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php">';
		exit;		
	 }
	
	$to_name = $row['name'];
	$from_name = $row2['name'];
?>


<div id="beautiful_msg2">
   <form method="post" enctype="multipart/form-data" name="message_form" action="utils/sendmessage.php">    
        <label class="from">From</label>
		<input type="hidden" name="from_id" size="60" value="<?php echo $uid; ?>"/>
        <input type="hidden" name="pid" size="60" value="<?php echo $pid; ?>"/>
        <input disabled="disabled" class="tmail" type="text" name="from_name" style="width:400px;" value="<?php echo $from_name; ?>"/> <br/>
        <label class="to">To</label>
        <input type="hidden" name="to_id" size="60" value="<?php echo $to; ?>"/>
		<input disabled="disabled" class ="tmail" type="text" name="to_name" style="width:400px;" value="<?php echo $to_name; ?>"/> <br/>
        <label class="subject">Subject</label>
		<input class="tmail" type="text" name="subject"  maxlength="255" style="width:400px;" /> <br/>
        
		<textarea name="text" id="text-area" maxlength="4500" onkeyup="return ismaxlength(this)" onkeypress="resize(this)" class="tmail" style="width:500px;" rows="8"></textarea><br />
        <div id="attach">
        <a href="javascript:attach();"><img src="images/attach.png" width="16" height="16" alt="attach" id="attach_button" /></a><br />
        <label id="infolabel" class="bigger2"><small>You can attach one JPG image to your message. (Max. size 4 MB)</small></label>
        </div>        
        <input type="file" style="visibility:hidden;float:right;margin-top:10px; width:300px;" id="attach_input" name="attach_file" onchange="javascript:addfilename();" />
        <button class="bmail" type="submit" value="Send">Send</button>
        <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
    </form>
</div>
  <script type="text/javascript" language="javascript">
  var restrict = 0;
  function resize(t) {
	if(restrict < 45){
	a = t.value.split('\n');
	b=1;
	for(x=0;x < a.length; x++) { 
	if (a[x].length >= t.cols) {
	b+= Math.floor(a[x].length/t.cols);
	 }
	}
	b+= a.length;
	restrict++;
	if (b > t.rows) 
	t.rows = b;
   }
  }
  </script>



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

		function attach() {
			if (getIE() == -1){ 
				document.getElementById('attach_input').click();
			} else {
				document.getElementById('attach').style.display = "none";
				document.getElementById('attach_input').style.visibility = "visible";
			}
		}
		function addfilename() {			
			str = document.getElementById('attach_input').value;
			str = str.replace(/^.*\\/, '');
			document.getElementById('infolabel').innerHTML = "Attachment: "+str;
		}
		</script>



<?php else :?>
 <div id="bar">
<a href="messages.php">Inbox</a>
<a href="messages.php?s=sent">Outbox</a>
<a href="javascript: submitform()">Delete</a>
</div>

<?php
/*Inbox Delete Algorithm */
  $value = $_POST['value'];
 if($value == '1'){
  if(!isset($_POST['checkbox']))
  {
	  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php?d=er">';
	  exit();
   }
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);
 if(isset($_POST['checkbox']))
		{
	foreach($_POST['checkbox'] as $extra){
   $delete = mysql_query("UPDATE message SET disp_r = '0', didread = '1' WHERE mid='$extra'");
     	}
	   }
	   mysql_close($mysql_link);
     } 
?>
<?php
 /* This is the Inbox Section 
  *
  *
  *
  */
   if(!$sent == 'sent'):
   $mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$querydel = "SELECT * FROM message WHERE message.to_uid='$uid'";
	$resultdel = mysql_query($querydel, $mysql_link);
	 while($delrow = mysql_fetch_array($resultdel)){
		 $delrowmid = $delrow['mid'];
		if($delrow['disp_s'] == '0' && $delrow['disp_r'] == '0'){
		$forcedelete = mysql_query("DELETE FROM message WHERE mid='$delrowmid'");
		}	 
	 }
	$query = "SELECT COUNT(mid) FROM message WHERE to_uid='$uid' AND disp_r = '1' "; 
	$result = mysql_query($query, $mysql_link); 
	$row = mysql_fetch_row($result); 
	$total_records = $row[0];
	if($total_records == 0){
	}else{
	echo "<p style=\"margin:3px 0;\"><small>".$total_records." messages</small></p>\r\n";
	}
	 if($errordel == 'er'){
	 echo "<p class=\"error\">You did not select a message to be deleted</p>";	
	 }
	if ($total_records>$messages_per_page):
	$pages = ceil($total_records/$messages_per_page);
	//display pages
	
	$ista = 0;
	$iend = $pages;
	if ($pages>9) {
		$ista = $search_page - 4;
		$iend = $search_page + 4;
		if ($ista < 0) {
			$ista = 0;
			$iend = 9;
		}
		if ($iend > $pages) {
			$iend = $pages;
			$ista = $iend - 8;
		}
	}	
		
    $bar = "<div class=\"bar2\"><p><strong>Pages: </strong>";
	if ($ista>0) $bar .= "... ";
			for ($i = $ista; $i < $iend; $i++) {
				$bar.= "<a href=\"messages.php?page=$i\"".($search_page==$i?" class='active' ":"").">";
				$bar.= ($i+1);
				$bar.= "</a>\r\n";
			}
    if ($iend<$pages) $bar .= " ... ";
	$bar .= "</p></div>	\r\n";
	echo $bar;
	endif;
	//Inbox Messages Queries 

    $search_page = $search_page * $messages_per_page;
	$query = "SELECT message.*, profile.name, profile.uid FROM message, profile WHERE message.to_uid='$uid' AND profile.uid = message.from_uid AND message.disp_r ='1' ORDER BY message.datetime DESC LIMIT $search_page, $messages_per_page ";
	$result = mysql_query($query, $mysql_link);
	if($total_records == 0){
		echo "<p class=\"error\">Your mailbox is empty.</p>";
	}else{
		echo "<form name=\"sent\"  action=\"messages.php?r=r\" method=\"post\">\r\n";
		echo "<table cellpadding=\"2px\" class=\"wide\" cellspacing=\"1px\">\r\n";
		echo "<tr>";
		echo "<th class =\"select\"><input type=\"checkbox\" id=\"switch\" class=\"check\" onchange=\"javascript:magic('sent');\"/></th>";
		echo "<th class =\"from\">From</th>";
		echo "<th class =\"subj\">Subject</th>";
		echo "<th class =\"date\">Date</th>";
		echo "</tr>\r\n";
	}	   
	while ($row = mysql_fetch_array($result)) {
		$read = $row['didread'];
		$mid = $row['mid'];
		if($row['didread'] == '0'){
			$number += calculate_message($read);
		 }
	$n_w_tags = strlen($row['subject'])-strlen(strip_tags($row['subject']));
	if (strlen(strip_tags($row['subject']))>40) 					// if subject > 20 symbols, cut it
		$subj = substr($row['subject'],0,40+$n_w_tags)."...";
		else $subj = $row['subject'];
		
	$ddate = date('M, j h:i a', strtotime($row['datetime']));	
	$todaytime = date('h:i a', strtotime($row['datetime']));
	$today = date("Y-m-d");
	if($row['didread'] == 0)
			echo "<tr class=\"new\">";
		  else
			echo "<tr class=\"old\">";
		echo "<td class=\"type".$row['type']."\"><input type=\"checkbox\" name=\"checkbox[]\" value=\"$mid\" class=\"check\"/>"; //type = 1 means message, = 0 means smile
		echo "<input type=\"hidden\" name=\"value\" value=\"1\"/></td>\r\n";
		echo "<td><a href=\"profile.php?id=".$row['from_uid']."\">".$row['name']."</a></td>";
		echo "<td><a href=\"replymessage.php?messageid=".$row['mid']."\">".$subj."</a></td>";
		if($today == date('Y-m-d',strtotime($row['datetime']))){
		echo "<td>Today ".$todaytime."</td>";
		}else{
		echo "<td>".$ddate."</td>";
			}   
		echo "</tr>\r\n";
} //FOR LOOP
	echo "</table></form>\r\n";
	if ($total_records>$messages_per_page) echo $bar;

mysql_close($mysql_link);
 /* END INBOX SECION */  
?> 
<?php else : ?>
<?php 
/*Sent Delete Algorithm */
  $value = $_POST['value'];
 if($value == '2'){
   if(!isset($_POST['checkbox']))
  {
	  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php?s=sent&d=er">';
	  exit();
   }
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);
 if(isset($_POST['checkbox'])){
	foreach($_POST['checkbox'] as $extra){
   $delete = mysql_query("UPDATE message SET disp_s = '0' WHERE mid='$extra'");
     	}
	   }
	 mysql_close($mysql_link);
	}
?>
<?php
/* THIS STARTS THE SENT PART 
 *
 *
 */
   $mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
    //count number of persons to find the number of pages
	$querydel = "SELECT * FROM message WHERE message.from_uid='$uid'";
	$resultdel = mysql_query($querydel, $mysql_link);
	 while($delrow = mysql_fetch_array($resultdel)){
		 $delrowmid = $delrow['mid'];
		if($delrow['disp_s'] == '0' && $delrow['disp_r'] == '0'){
		$forcedelete = mysql_query("DELETE FROM message WHERE mid='$delrowmid'");
		}	 
	 }
	$query = "SELECT COUNT(mid) FROM message WHERE disp_s='1' AND from_uid='$uid'"; 
	$result = mysql_query($query, $mysql_link); 
	$row = mysql_fetch_row($result); 
	$total_records = $row[0];
    if($total_records == 0){
	}else{
	echo "<p style=\"margin:3px 0;\"><small>".$total_records." messages</small></p>\r\n";
	}
	 if($errordel == 'er'){
	 echo "<p class=\"error\">You did not select a message to be deleted</p>";	
	 }
	if ($total_records>$messages_per_page):
	$pages = ceil($total_records/$messages_per_page);
	//display pages
	
	$ista = 0;
	$iend = $pages;
	if ($pages>9) {
		$ista = $search_page - 4;
		$iend = $search_page + 4;
		if ($ista < 0) {
			$ista = 0;
			$iend = 9;
		}
		if ($iend > $pages) {
			$iend = $pages;
			$ista = $iend - 8;
		}
	}	
		
    $bar = "<div class=\"bar2\"><p><strong>Pages: </strong>";
	if ($ista>0) $bar .= "... ";
			for ($i = $ista; $i < $iend; $i++) {
				$bar.= "<a href=\"messages.php?s=sent&page=$i\"".($search_page==$i?" class='active' ":"").">";
				$bar.= ($i+1);
				$bar.= "</a>\r\n";
			}
    if ($iend<$pages) $bar .= " ... ";
	$bar .= "</p></div>	\r\n";
	echo $bar;
	endif;
		
    //Sent Messages Queries 
	
	 $search_page = $search_page * $messages_per_page;
	$query = "SELECT message.*, profile.name FROM message, profile WHERE message.from_uid='$uid' AND profile.uid = message.to_uid AND message.disp_s ='1' ORDER BY message.datetime DESC LIMIT $search_page, $messages_per_page ";
	$result2 = mysql_query($query, $mysql_link);
	$rowcheck = mysql_fetch_array($result);
	if($total_records == 0){
		echo "<p class=\"error\">Your mailbox is empty.</p>";
	}else{
		echo "<form name=\"sent\" action=\"messages.php?s=sent\" method=\"post\">\r\n";
		echo "<table class=\"wide\" cellpadding=\"2px\" cellspacing=\"1px\">\r\n";
		echo "<tr>";
		echo "<th class =\"select\"><input type=\"checkbox\" id=\"switch\" class=\"check\" onchange=\"javascript:magic('sent');\"/></th>";
		echo "<th class =\"to\">To</th>";
		echo "<th class =\"subj\">Subject</th>";
		echo "<th class =\"date\">Date</th>";
		echo "</tr>\r\n";
	}	    
	while($row2 = mysql_fetch_array($result2)){
	$read = $row2['didread'];
	$mid2 = $row2['mid'];
	
	if($row2['didread'] == '0'){
	$number += calculate_message($read);
	 }
	//strip_tags() 
	$n_w_tags = strlen($row2['subject'])-strlen(strip_tags($row2['subject']));
	if (strlen(strip_tags($row2['subject']))>40) 					// if subject > 20 symbols, cut it
		$subj = substr($row2['subject'],0,40+$n_w_tags)."...";
		else $subj = $row2['subject'];
		
	$ddatesent = date('M, j h:i A', strtotime($row2['datetime']));	
	$todaytimesent = date('h:i A', strtotime($row2['datetime']));
	$today = date("Y-m-d");
    	if($row2['didread'] == 0)
			echo "<tr class=\"new\">";
		  else
			echo "<tr class=\"old\">";
		echo "<td class=\"type".$row2['type']."\"><input type=\"checkbox\" name=\"checkbox[]\" value=\"$mid2\" class=\"check\"/>"; //type = 1 means message, = 0 means smile
		echo "<input type=\"hidden\" name=\"value\" value=\"2\"/></td>\r\n";
		echo "<td><a href=\"profile.php?id=".$row2['to_uid']."\">".$row2['name']."</a></td>\r\n";
		echo "<td><a href=\"replymessage.php?messageid=".$row2['mid']."&s=s\">".$subj."</a></td>";
		if($today == date('Y-m-d',strtotime($row2['datetime']))){
		echo "<td>Today ".$todaytimesent."</td>";
		}else{
		echo "<td>".$ddatesent."</td>";
		}
		echo "</tr>\r\n";
} //FOR LOOP
	echo "</table></form>\r\n";

if ($total_records>$messages_per_page) echo $bar;

mysql_close($mysql_link);
 
endif;
endif;
/* END SEND MESSAGE PAGE */
?>

 <script type="text/javascript">
function submitform(){
 document.forms["sent"].submit();
 }
</script>
<script type="text/javascript">
function addToContacts(){
	$.post("utils/addtocontacts.php", {myid: <?php echo $uid;?>, id: <?php echo $to;?>});
	 $('#contact_link').fadeOut('slow', function() {
        document.getElementById('contact_link').style.display = "none";
		document.getElementById('incontact').style.display = "inline";
		$('#incontact').fadeIn('slow');
      });
}
</script>
 </div> <!-- profile -->
</div> <!-- right -->


<?php
	include ("footer.php");
?>

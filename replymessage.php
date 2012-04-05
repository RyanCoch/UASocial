<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

	include ("top/header2.php");
	date_default_timezone_set("America/New_York");
    $messageid = $_GET['messageid'];
	$today = date("Y-m-d H:i:s");
	$sentpage = $_GET['s'];
	
	$uid = $_SESSION['uid'];			//my info
	$name = $_SESSION['name'];			//
	$picture = $_SESSION['picture'];	//
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);		
	
?> 
    
    <div id="left" style="min-height:750px;">
        <!-- empty field -->

        <?php include("utils/leftmenu.php");?>
    
    </div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
	<h2>Messages</h2>

    <div id="bar">
        <a href="messages.php">Inbox</a>
        <a href="messages.php?s=sent">Outbox</a>
        <?php if($sentpage != 's' && $sentpage != 'r' ):?>
			<a href="replymessage.php?messageid=<?php echo $messageid;?>&s=r">Reply</a>
        <?php endif;?>
	</div>
    
  <?php
  
		if(!$sentpage || $sentpage=='r') {
			//inbox or reply
			$query3 = "SELECT message.*, profile.name FROM message, profile WHERE mid='$messageid' AND message.to_uid='$uid' AND profile.uid = message.from_uid";
			$query4 = "UPDATE message SET didread = '1' WHERE mid = '$messageid' AND to_uid = '$uid'";
		} else {		
			//sent
			$query3 = "SELECT message.*, profile.name FROM message, profile WHERE mid='$messageid' AND message.from_uid='$uid' AND profile.uid = message.to_uid";
			$query4 = "UPDATE message SET didread = '1' WHERE mid = '$messageid' AND to_uid = '$uid'";
		}
		
		$update_didread = mysql_query($query4, $mysql_link);
		$result = mysql_query($query3, $mysql_link);
		$row2 = mysql_fetch_array($result);
		if (!$result || !$row2) {
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=messages.php">';
			exit;	
		}
		
		
		$to = $row2['from_uid'];
		$toname = $row2['name'];
		$subject = $row2['subject'];
		$text = nl2br($row2['text']);		//nl2br FUNCTION LET US DISPLAY THE RETURN CHARACTERS	
		
		echo "<div id=\"beautiful_msg3\">";
		echo "<p>";
		echo "<span class=\"left from\">From</span><span class=\"right\">".(($sentpage != 's')?$row2['name']:$name)."</span><br />\r\n";
		echo "<span class=\"left to\">To</span><span class=\"right\">".(($sentpage != 's')?$name:$row2['name'])."</span><br />\r\n";
		echo "<span class=\"left subj\">Subject</span><span class=\"right\">".$subject."</span><br />\r\n";
		echo "<span class=\"right msg\">".$text."</span>\r\n";
		echo "</p>";
		echo "</div>\r\n";

    if($sentpage == 'r'):		//opening sent message

    ?>
        <br /><h3>Reply Message</h3>
       	<div id="beautiful_msg2">
     <form name="include" action="" method="post">
     <label for="include" id="inc_l" class="bigger">Include the original message</label>
	 <input class="styled2" type="checkbox" id="inc" name="include" value="<?php $text; ?>"  onclick="document.include.submit();" <?php if(isset($_POST['include'])) echo "checked"; ?> />
     <input type="hidden" name="includevalue" value="4"/>
     </form>
        <form method="post" enctype="multipart/form-data" name="message_form" action="utils/sendreply.php">    
            <label for="from">From</label>
            <input type="hidden" name="from_id" size="60" value="<?php echo $uid; ?>"/>
            <input disabled="disabled" class="tmail" type="text" name="from_name" style="width:400px;" value="<?php echo $name; ?>"/> <br/>
            <label for="to">To</label>
            <input type="hidden" name="to_id" size="60" value="<?php echo $to; ?>"/>
            <input disabled="disabled" class="tmail" type="text" name="to_name" style="width:400px;" value="<?php echo $toname; ?>"/> <br/>
            <label for="subject">Subject</label>
            <input class="tmail" type="text" name="subject" maxlength="255" style="width:400px;" value="Re:<?php echo strip_tags($subject) ?>"/> <br/>
            <?php 
			$value = $_POST['includevalue'];
  			if($value == '4'){
			if(isset($_POST['include'])){
			$textvalue = "\r\n\r\n-----\r\n".strip_tags($text);
			?>
			<script type="text/javascript">
				function disable(){
					document.getElementById('inc_').className += " invisible";
					document.getElementById('inc_l').style.display = "none";
				}
				
				function ismaxlength(obj){
	var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
	if (obj.getAttribute && obj.value.length>mlength)
	obj.value=obj.value.substring(0,mlength)
}	

            </script>
			<?php
			   }
			}
			?>
		     <textarea name="text" class="tmail" id="textfield" maxlength="4500" onkeyup="return ismaxlength(this)" style="width:500px;" rows="8" onkeydown="disable();" /><?php echo $textvalue; ?></textarea><br />
            <div id="attach">
            <a href="javascript:attach();"><img src="images/attach.png" width="16" height="16" alt="attach" id="attach_button" /></a><br />
            <label id="infolabel" class="bigger2"><small>You can attach one JPG image to your message. (Max. size 4 MB)</small></label>
            </div>        
	        <input type="file" style="visibility:hidden;float:right;margin-top:10px; width:300px;" id="attach_input" name="attach_file" onchange="javascript:addfilename();" />
             <button class="bmail" type="submit" value="send">Send</button>
             <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
        </form>
        </div>

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
			document.getElementById('inc_l').style.display = "none";
			document.getElementById('inc_').className += " invisible";	
		}
		</script>        

<?php endif; ?>
   </div>

<?php
if(!$sentpage || $sentpage=='r'):
?>
    <p><span class="report">
	<a href="report.php?uid=<?php echo $uid; ?>&id=<?php echo $to;?>">
	<img src="images/report.png" border="0px" />
    report/block
	</a>
    </span></p>

<?php endif;
?>

 <script type="text/javascript">
function submitform(){
 document.forms["include"].submit();
 }
</script>
    </div>
<?php
	include ("footer.php");
	mysql_close($mysql_link);
		
?>  
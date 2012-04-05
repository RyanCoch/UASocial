<?php
$a = session_id();
if(empty($a)) session_start();
$_SESSION['var1'] = '1';

$_SESSION['display_new_here'] = 1;
	include ("top/header2.php");
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
	include_once("utils/settings.php");
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$z = addminutes(time(),2);
	$uid = $_SESSION['uid'];
	$result = mysql_query("UPDATE `online` SET `time` = '$z', `type` = '1'  WHERE uid='$uid'") or die(mysql_error());
	$_SESSION['online'] = $z;
?>

<script type="text/javascript">
	function loadOnline(){		
		$.ajax({
			url: "getonlinelist.php",
			cache: false,
			success: function(html){	
			$("#ch_online").html(html);				
		  	},
		});
	}
	setInterval (loadOnline, 15000);	//Reload file every 30 seconds*/
	
</script>
<div id="left" style="min-height:750px;">
<?php 
$_SESSION['display_new_here'] = 1;
include("utils/leftmenu.php");?>

</div>

<div id="right" style="min-height:750px;">
 <div id="profile">
 <h2>Chat room</h2>
 
	<div id="chatbox">
	<?php
	include_once("log.php");
	
	?></div>
   
<script type="text/javascript">
	function sendName(name){		
	var MyElement = document.getElementById("about");
	var clientmsg = document.getElementById("about").value;
    MyElement.value =  clientmsg  + name + ', ';
	MyElement.focus();
	}
</script>
    <form name="message" action="" id="chat_message_form">
    <label id="atext" style="width:500px; color:#999; font-size:.9em;">&nbsp;</label>
    <input name="usermsg"  class="tsearch" type="text" id="about" size="75" style="height:26px;width:480px;" onkeyup="javascript:textarea(200, ' ');" />
	<button class="bsearch" type="submit" id="submitmsg" onclick="default();" >Say</button>
	</form>
     <?php //FUCKING SMILEY'S :) ?>
    <img src="smileys/angry.gif" alt="Angry" onclick="Angry()" />&nbsp;<img src="smileys/blank.gif" alt="Blush" onclick="Blank()" />&nbsp;<img src="smileys/blush.gif" alt="Blush" onclick="Blush()" />
    &nbsp;<img src="smileys/cool.gif" alt="Cool" onclick="Cool()" />&nbsp;<img src="smileys/cry.gif" alt="Cry" onclick="Cry()" />&nbsp;<img src="smileys/dizzy.gif" alt="Dizzy" onclick="Dizzy()" />
    &nbsp;<img src="smileys/happy.gif" alt="Happy" onclick="Happy()" />&nbsp;<img src="smileys/kiss.gif" alt="Kiss" onclick="Kiss()" />&nbsp;<img src="smileys/laugh.gif" alt="Laugh" onclick="Laugh()" />
    &nbsp;<img src="smileys/puzzled.gif" alt="Puzzled" onclick="Puzzled()" />&nbsp;<img src="smileys/sad.gif" alt="Sad" onclick="Sad()" />&nbsp;<img src="smileys/shocked.gif" alt="Shocked" onclick="Shocked()" /> &nbsp;<img src="smileys/sleep.gif" alt="Sleep" onclick="Sleep()" />&nbsp;<img src="smileys/smiley.gif" alt="Smiley" onclick="Smiley()" />&nbsp;<img src="smileys/sneaky.gif" alt="Sneaky" onclick="Sneaky()" />&nbsp;<img src="smileys/tongue.gif" alt="Tongue" onclick="Tongue()" />&nbsp;<img src="smileys/uhoh.gif" alt="UhOh" onclick="UhOh()" />&nbsp;<img src="smileys/uneasy.gif" alt="Uneasy" onclick="Uneasy()" />
    &nbsp;<img src="smileys/wideeye.gif" alt="Wideeye" onclick="Wideeye()" />&nbsp;<img src="smileys/wink.gif" alt="Wink" onclick="Wink()" />&nbsp;<img src="smileys/santa.gif" alt="Santa" onclick="Santa()" />
</div></div>
<script type="text/javascript" language="JavaScript">
   document.getElementById('about').focus();
</script>
<script type="text/javascript" language="JavaScript">
	function default(){
		document.getElementById('about').focus();
	}
</script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
	//If user submits the form
	$("#submitmsg").click(function(){	
		var clientmsg = $("#about").val();
		$.post("post.php", {text: clientmsg});				
		$("#about").attr("value", "");
		return false;
	});
	
	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		
		$.ajax({
			url: "log.php",
			cache: false,
			success: function(html){	
			$("#chatbox").html(html);				
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				 }				
		  	},
		});
	}
	setInterval (loadLog, 2500);	//Reload file every .10 seconds
});
</script>

<script type="text/javascript">
function scrolldown(){
	var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "log.php",
			cache: false,
			success: function(html){	
			$("#chatbox").html(html);				
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
				if(newscrollHeight <= oldscrollHeight){
		
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 0); //Autoscroll to bottom of div
			
				}				
		  	},
		});
 }
</script>

<script type="text/javascript" language="javascript">

function Angry()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ">:(";
	MyElement.focus();
    return true;
}
function Blank()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":|";
	MyElement.focus();
    return true;
}
function Blush()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":X";
	MyElement.focus();
    return true;
}
function Cool()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "B-)";
	MyElement.focus();
    return true;
}
function Cry()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":-(";
	MyElement.focus();
    return true;
}
function Dizzy()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "*-*";
	MyElement.focus();
    return true;
}
function Happy()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":-)";
	MyElement.focus();
    return true;
}
function Kiss()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "=*";
	MyElement.focus();
    return true;
}
function Laugh()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":D";
	MyElement.focus();
    return true;
}
function Puzzled()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "O.o";
	MyElement.focus();
    return true;
}
function Sad()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":(";
	MyElement.focus();
    return true;
}
function Shocked()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":o";
	MyElement.focus();
    return true;
}
function Sleep()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "I-)";
	MyElement.focus();
    return true;
}
function Smiley()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":)";
	MyElement.focus();
    return true;
}
function Sneaky()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":->";
	MyElement.focus();
    return true;
}
function Tongue()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":p";
	MyElement.focus();
    return true;
}
function UhOh()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "=-o";
	MyElement.focus();
    return true;
}
function Uneasy()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ":/";
	MyElement.focus();
    return true;
}
function Wideeye()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "8)";
	MyElement.focus();
    return true;
}
function Wink()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + ";)";
	MyElement.focus();
    return true;
}
<!-- Holiday Similes -->
function Santa()
{
    var MyElement = document.getElementById("about");
	var clientmsg = $("#about").val();
    MyElement.value =  clientmsg + "x*x";
	MyElement.focus();
    return true;
}

</script>
</div> <!-- profile -->
</div> <!-- right -->


<?php
	include ("footer.php");
		
	//mysql_close($mysql_link);

?>
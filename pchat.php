<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UASocial chat</title>

<script type="text/javascript">
function minChat(w) {
	$.post("utils/change_pchat_status.php", {win: w, stat: 2});
	parent.minimizeChat(w);
}
function cloChat(w) {
	$.post("utils/change_pchat_status.php", {win: w, stat: 0});
	parent.closeChat(w);
}
</script>

<?php 
unset($name, $picture, $uid, $menu_type, $css);
$menu_type = $_SESSION['menu_type'];
if (isset($_SESSION['css']))
	$css = $_SESSION['css'];
	else
	$css = '2';
	
if ($css == '0'):
?>
<link href="css/uadn.css" rel="stylesheet" type="text/css" />
<link href="css/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '1') :
?>
<link href="css/uadn.css" rel="stylesheet" type="text/css" />
<link href="images/minimal/uadn.css" rel="stylesheet" type="text/css" />
<link href="images/minimal/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '2') :
?>
<link href="css/uadn_alt.css" rel="stylesheet" type="text/css" />
<link href="css/button_alt.css" rel="stylesheet" type="text/css" />
<?php endif;
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

</head>

<body>

<?php
	echo "<body onLoad=\"javascript:scrolldown();\">\r\n";
	include_once("utils/settings.php");

   $name = $_SESSION['name'];
   $uid = $_SESSION['uid'];
   $to = $_GET['to']; 
   $window = $_GET['window'];
 
  //Getting the logfile for the session 
  $mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
  mysql_select_db($mysql_database, $mysql_link);
  
  $result = mysql_query("SELECT * FROM pchat WHERE (user1 = '$uid' AND user2 = '$to') OR (user1 = '$to' AND user2 = '$uid')") or die(mysql_error());
  $get_log = mysql_fetch_array($result);
  $directory = "pchatlogs/";
  $log = $directory . $get_log['logfile'];
  
  //find the name
  if ($get_log['user1'] == $uid)
  	$userid = $get_log['user2'];
	else 
  	$userid = $get_log['user1'];

  $result = mysql_query("SELECT name FROM profile WHERE `uid` = '$userid' ") or die(mysql_error());
  $get_log = mysql_fetch_array($result);
  $username = $get_log['name'];

?>

    <div class="headd">
	    <a href="javascript:minChat(<?php echo $window;?>);"><strong><?php echo $username;?></strong></a>
        &nbsp;<a href="javascript:cloChat(<?php echo $window;?>);"><strong>X</strong></a>
    </div>
    
    <div class="body">
    
	<div id="pchatbox">
		<?php
        if(file_exists($log) && filesize($log) > 0){
            $handle = fopen($log, "r");
            $contents = fread($handle, filesize($log));
            fclose($handle);	
            echo $contents;
        }
        ?>
	</div>
   
<script type="text/javascript">
	function sendName(name){		
		var MyElement = document.getElementById("aboutx");
		var clientmsg = $("#aboutx").val();
		MyElement.value =  clientmsg  + name + ', ';
		MyElement.focus();
		return;
	}
</script>
    
    <form name="message" id="chat_message_form">
    <input type="hidden" id="logfile" value="<?php echo $log; ?>"/>
    <input name="usermsg"  class="tsearch" type="text" id="aboutx" maxlength="200" style="width:200px; height:20px; margin:0; padding:0;" />
	<button class="bsearch" id="submitmsgx" style="height:20px; margin:0; padding:0;" >Say</button>
	</form>

</div>

<script type="text/javascript">
	
	document.getElementById('aboutx').focus();
</script>
<script type="text/javascript">
	
	$(document).ready(function(){
    var logfile_id =  $("#logfile").val();
	$("#submitmsgx").click(function(){	
		var clientmsg = $("#aboutx").val();
		$.post("ppost.php", {text: clientmsg, logfile: logfile_id});				
		$("#aboutx").attr("value", "");
		//$("#logfile").attr("value", "");
		return false;
	});
	
	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#pchatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "<?php echo $log ?>",
			cache: false,
			success: function(html){	
			$("#pchatbox").html(html);				
				var newscrollHeight = $("#pchatbox").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#pchatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				 }				
		  	},
		});
	}
	setInterval (loadLog, 2500);	//Reload file every .10 seconds
	
});
</script>
<script type="text/javascript">
function scrolldown(){
	var oldscrollHeight = $("#pchatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "<?php echo $log ?>",
			cache: false,
			success: function(html){	
			$("#pchatbox").html(html);				
				var newscrollHeight = $("#pchatbox").attr("scrollHeight") - 20;
				if(newscrollHeight <= oldscrollHeight){
					$("#pchatbox").animate({ scrollTop: newscrollHeight }, 0); //Autoscroll to bottom of div
				}				
		  	},
		});
 }

</script>
</body>
</html>
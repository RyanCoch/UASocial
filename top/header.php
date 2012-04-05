<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noodp,noydir" />
<meta name="description" content="UASocial is a network for University at Albany Students. A safe and private place for students to interact and connect with others students from the University." />
<title>UASocial</title>
<?php 
include_once("/home/uasocial/public_html/utils/settings.php");

unset($name, $picture, $uid, $menu_type, $css);
$menu_type = $_SESSION['menu_type'];
if (isset($_SESSION['css']))
	$css = $_SESSION['css'];
	else
	$css = '2';
	
if ($css == '0'):
?>
<link href="<?php echo $urladdress;?>/css/uadn.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urladdress;?>/css/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '1') :
?>
<link href="<?php echo $urladdress;?>/css/uadn.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urladdress;?>/images/minimal/uadn.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urladdress;?>/images/minimal/button.css" rel="stylesheet" type="text/css" />
<?php elseif ($css == '2') :
?>
<link href="<?php echo $urladdress;?>/css/uadn_alt.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urladdress;?>/css/button_alt.css" rel="stylesheet" type="text/css" />
<?php endif;
?>

<link rel="shortcut icon" href="<?php echo $urladdress;?>/images/gicon.ico" type="image/x-icon" />

<style type="text/css">
input.styled { display: none; }
input.styled2 { display: none; }
input.styled3 { display: none; }
.disabled { opacity: 0.5; filter: alpha(opacity=50); }
.invisible { display: none; }
</style>


<script type="text/javascript">
function login()
{
	document.getElementById("registration").style.display = "none";
	document.getElementById("login").style.display = "inline";
}
function register()
{
	document.getElementById("registration").style.display = "inline";
	document.getElementById("login").style.display = "none";
}
function checkpwd(i) {
	p1 = document.getElementById("pwd1").value;
	p2 = document.getElementById("pwd2").value;
	if (p1==p2) {document.getElementById("match").style.display = "none"; }
	if ((i==1) && (p1=="")) { document.getElementById("match").style.display = "inline"; }
	if ((i==2) && (p2=="")) { document.getElementById("match").style.display = "inline"; }
	if ((i==2) && (p2!="")  && (p2!=p1)) { document.getElementById("match").style.display = "inline"; }
}
function textarea(length, text) {
	p1 = document.getElementById("about").value;
	p2 = p1.length;
	document.getElementById("atext").innerHTML = text + " <small>("+(length-p2)+" symbols left)</small>";
}

function pchat(to_id) {
		$.post("<?php echo $urladdress;?>/utils/init_pchat.php", {to: to_id});
		displayChat(to_id);
}
</script>

<script type="text/javascript" src="<?php echo $urladdress;?>/js/pchat.js"></script>
<script type="text/javascript" src="<?php echo $urladdress;?>/utils/encript.js"></script>
<script type="text/javascript" src="<?php echo $urladdress;?>/utils/custom.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27031013-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php
$am_i_in_chat = $_SESSION['display_new_here'];
$_SESSION['display_new_here'] = 0;
?>

<script type="text/javascript">
	function loadmessage(){		
		$.ajax({
			url: "<?php echo $urladdress;?>/newmsg.php?<?php if ($am_i_in_chat) echo "chat=1";?>",
			cache: false,
			success: function(html){	
			$("#new_messages").html(html);				
		  	},
		});
	}
	setInterval (loadmessage, 5000);	
</script>




</head>

<?php
$type = $_GET['f'];
$var1 = $_SESSION['var1'];
if ($type=='1') {
	echo "<body onLoad=\"javascript:login();\">\r\n";
} elseif ($var1=='1') {
	echo "<body onLoad=\"javascript:scrolldown();\">\r\n";
} else{
	echo "<body>\r\n";
}
$_SESSION['var1'] = '0';
	
?>

<div id="upper">
<div id="middle">

<?php 

if($_COOKIE['eml'] && $_COOKIE['pwd']){
include(inner()."verify.php");	
}

if(isset($_SESSION['uid'])) :

	$name = substr($_SESSION['name'],0,30);
	$uid = $_SESSION['uid'];
	$picture = $_SESSION['picture'];
	date_default_timezone_set("America/New_York");
	
	?>
	
	<div id="header"><a href="/wall.php"><img src="/images/transparent.png" width="150" height="40" /></a>
	</div>
	
	<div id="topmenu">
	<p class="topmenu">
		<a href="/utils/logout.php">Log out</a>
		<a href="/settings.php">Settings</a>
		<a href="/search.php" class="last"><img src="/images/alt/lupa.png" height="30" />Search</a>	
		<a href="/chat.php" class="last"><img src="/images/alt/chat.png" height="30" />Chat room</a>	
		<a href="/myprofile.php" class="last"><img src="<?php if (file_exists(inner()."pictures/".$picture."30.jpg")) echo "/pictures/".$picture."30.jpg"; else echo "/pictures/0/boy30.jpg"; ?>" alt="My Face" width="30" height="30" /> <?php echo $name;?></a>
	</p>
	</div>


<?php else:
// if session is not registered
 ?>


<div id="header"><a href="/index.php"><img src="/images/transparent.png" width="150" height="40" /></a>
</div>


<div id="topmenu">
<p class="topmenu">
	<a href="/index.php?f=1">Log in</a>
    <a href="/about.php">About</a>	
</p>
</div>


<?php endif; ?>

<div style="clear:both; padding:0px; margin:0px;"></div>
</div><!-- middle -->
</div><!-- upper -->

<?php


if ( !isset( $_SESSION["pchat_a"] ) ) { 
    $_SESSION["pchat_a"] = array(); 
    $_SESSION["pchat_b"] = array(); 
} 


if ($_SESSION['pchat_a'][1] != '0' && $_SESSION['pchat_b'][1] > '0') {
	echo "<script type=\"text/javascript\">\r\n $(document).ready(function(){displayChat(".$_SESSION['pchat_a'][1].");});\r\n</script>\r\n";
	$_SESSION['pchat_a'][1] = "0";
}
if ($_SESSION['pchat_a'][2] != '0' && $_SESSION['pchat_b'][2] > '0') {
	echo "<script type=\"text/javascript\">\r\n $(document).ready(function(){displayChat(".$_SESSION['pchat_a'][2].");});\r\n</script>\r\n";
	$_SESSION['pchat_a'][2] = "0";
}
if ($_SESSION['pchat_a'][3] != '0' && $_SESSION['pchat_b'][3] > '0') {
	echo "<script type=\"text/javascript\">\r\n $(document).ready(function(){displayChat(".$_SESSION['pchat_a'][3].");});\r\n</script>\r\n";
	$_SESSION['pchat_a'][3] = "0";
}
if ($_SESSION['pchat_a'][4] != '0' && $_SESSION['pchat_b'][4] > '0') {
	echo "<script type=\"text/javascript\">\r\n $(document).ready(function(){displayChat(".$_SESSION['pchat_a'][4].");});\r\n</script>\r\n";
	$_SESSION['pchat_a'][4] = "0";
}

?>
<!-- Private Chat Dedicated to Matthew Grunert -->
<div class="popup" id="popup1" style="display:none;"></div>
<div class="popup" id="popup2" style="display:none;"></div>
<div class="popup" id="popup3" style="display:none;"></div>
<div class="popup" id="popup4" style="display:none;"></div>

<div id="wrap">


<?php

include_once('/home/uasocial/public_html/utils/settings.php');

///////////////////////////////
//
//		ACHTUNG!! DRAGONS AHEAD!
//
//		Attention!
//		Function innner() always returns OUR root directory on server
//		/home/uasocial/public_html/
//
///////////////////////////////

$menu_type = $_SESSION['menu_type'];
$chat = ($_SESSION['display_new_here'] == '1');

$get_reg = mysql_query("SELECT uid, name, picture FROM profile WHERE (active > 0 AND picture NOT LIKE '0/%') ORDER BY uid DESC LIMIT 0,3 ", $mysql_link);
if (!$get_reg) {
	//disregard
 } else {
 $r = 0;
 while ($rz = mysql_fetch_array($get_reg))
	$regrow[$r++] = $rz;
 }

if ($menu_type && !$lastpic) {
	// new pictures uploaded
	$get_reg = mysql_query("SELECT pic_id, uid, picture FROM picture ORDER BY pic_id DESC LIMIT 0,9 ", $mysql_link);
	if (!$get_reg) {
		//disregard
	 } else {
		 $r = 0;
		 while ($rz = mysql_fetch_array($get_reg))
			$lastpic[$r++] = $rz;
	 }
}

unset($rz, $get_reg, $get_messages, $r);

if ($chat) $regrow = 0 ;

?>
        

<center>
<?php if ($chat) {
?>
<h2 class="name"><?php echo $name; ?></h2>  
<?php echo '<p class="incontact">&nbsp;</p>';?>
<a href="/myprofile.php">
<img src="<?php if (file_exists(inner()."pictures/".$picture."60.jpg")) echo "/pictures/".$picture."60.jpg"; else echo "/pictures/0/boy60.jpg"; ?>" alt="My Face" width="60" height="60" class="profile_picture" /></a>
</center>
<?php } else { ?>
<h2 class="name"><?php echo $name; ?></h2>  
<?php echo '<p class="incontact">&nbsp;</p>';?>
</center>
<a href="/myprofile.php">
<img src="<?php if (file_exists(inner()."pictures/".$picture."170.jpg")) echo "/pictures/".$picture."170.jpg"; else echo "/pictures/0/boy170.jpg"; ?>" alt="My Face" width="170" height="220" class="profile_picture" />
</a>
<?php } ?>


<ul class="leftmenu" id="big_menu" <?php if ($menu_type || $chat) echo "style=\"display:none;\""; ?>>
<li class="one"><a href="/myprofile.php"><img src="/images/alt/profile_icon.png" class="icon">My Profile</a></li>
<li class="three"><a href="/messages.php"><img src="/images/alt/message_icon.png" class="icon">Messages</a></li>
<li class="four"><a href="/pictures.php"><img src="/images/alt/picture_icon.png" class="icon">Pictures</a></li>
<li class="five"><a href="/settings.php"><img src="/images/alt/settings_icon.png" class="icon">Settings</a></li>
<?php	//check if i'm admin
    $flag = 0;
    for ($i = 0; $i < $number_of_admins; $i++)
       if ($adminid[$i] == $uid) $flag = 1;
       if ($flag) echo "<li class=\"green\"><a href=\"/admin/\"><img src=\"/images/admin_icon.png\" class=\"icon\">Admin</a></li>";
?>   
</ul>


<ul class="leftmenu_sm" id="small_menu" <?php if (!$menu_type && !$chat) echo "style=\"display:none;\""; ?>>
<li class="five"><a href="/settings.php"><img src="/images/alt/settings_icon.png" class="icon"></a></li>
<li class="four"><a href="/pictures.php"><img src="/images/alt/picture_icon.png" class="icon"></a></li>
<li class="three"><a href="/messages.php"><img src="/images/alt/message_icon.png" class="icon"></a></li>
<li class="one"><a href="/myprofile.php"><img src="/images/alt/profile_icon.png" class="icon"></a></li>
</ul>

<?php	//check if i'm admin
    $flag = 0;
    for ($i = 0; $i < $number_of_admins; $i++)
       if ($adminid[$i] == $uid) $flag = 1;
       if ($flag) {
			echo "<ul class=\"leftmenu_sm\" id=\"admin_menu\"".((!$menu_type && !$chat)?"style=\"display:none;\"":"").">";
		    echo "<li class=\"green\"><a href=\"/admin/\"><img src=\"/images/alt/admin_icon.png\" class=\"icon\"></a></li>";
			echo "</ul>";
	   }

echo "<div id=\"new_messages\">";
include (inner()."newmsg.php");
echo "</div>\r\n";


	if(!empty($regrow))	{	// display new users on the network
		echo "\r\n<div id=\"newhere\">";
		
		if (!$menu_type)
			include_once (inner()."utils/infoblock_short.php");
			else
			include_once (inner()."utils/infoblock_long.php");
		
		echo "</div>\r\n";
	}

if ($chat) {
	// div for the chat	 "ONLINE USERS"
	
	echo "<div id=\"ch_online\">\r\n";
	include_once(inner()."getonlinelist.php");
	echo "</div>\r\n";
	
	
}
$_SESSION['display_new_here'] = 0;

	?>
	
    <form id="set_menu_type_form" method="post" action="/utils/set_menu_type.php">
	    <input type="hidden" name="mnutype" value="<?php echo (($menu_type)?"0":"1")?>" />
    </form>
    
    <script type="text/javascript">
	function changeMenuView() {
		document.forms["set_menu_type_form"].submit();
	}
	</script>
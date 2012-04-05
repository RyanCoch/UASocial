<div id="news" style="background:url(images/bg.png) repeat;">

<div style="clear:both;">&nbsp;</div>
<center>
<?php
	include_once("utils/settings.php");
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);
	$query = mysql_query("SELECT COUNT(uid) FROM profile", $mysql_link) or die(mysql_error());
	$row = mysql_fetch_row($query); 
	$num_users = $row[0];
	mysql_close($mysql_link);

echo "<p style=\"font-size:3em; font-width:bold; margin-top:0px; color:#00551f;\">";
echo $num_users;
echo "</p>";
?>
<p style="font-size:1em; color:#00551f;">UAlbany students already joined this network!</p>
</center><br />



<div class="fb-like" data-href="http://www.facebook.com/pages/UASocial/227331780659512" data-send="false" data-width="450" data-show-faces="true" data-font="trebuchet ms"></div>

</div>
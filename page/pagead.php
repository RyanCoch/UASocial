<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}


//	include_once("/home/uasocial/public_html/utils/settings.php");
//	include_once(inner()."utils/functions.php");
	
//	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
//	mysql_select_db($mysql_database, $mysql_link);

?>

<div id="pagead">

<div id="pagead1" style="width:260px; float:left;">
<p><span style="font-size:1.5em; color:#666;">Introducing Group Pages</span></p>
<p align="center"><span style="font-size:1.3em; color:#00551f;"><a href="/page/NewPage.php" class="high">Create a new page</a></span></p>
</div>

<div id="pagead2" style="width:260px; text-align:center;float:left;">
<p><span style="font-size:1.3em; color:#666;">Find a Roommate on Campus!</span></p>
<a href="/page/?id=8"><img src="http://www.uasocial.com/page/pictures/8/v7gp1m6l60.jpg" style="position:relative; top:-11px;"/></a>
</div>






</div> <!-- page ad -->
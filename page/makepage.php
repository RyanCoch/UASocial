<?php
//Get the data
$uid = $_POST['uid'];
$pagename =  addslashes(strip_tags($_POST['name']));
$pagehead = addslashes(strip_tags($_POST['headline']));
$pagecat  = addslashes(strip_tags($_POST['catdropdown']));
$pageabout = addslashes(strip_tags($_POST['text']));
$pageright = $_POST['right'];
$pagedate = date('Y-m-d');

if(strlen($pagename) < 1){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/page/NewPage.php?e=pn">';
	exit;
}
?>

<p>Registration...</p>

<?php
include("../utils/settings.php");
addslashes($pagename);
if(strlen($pageabout) < 1){
	$pageabout = "Welcome to your new page on UASocial. This is your about section. You can edit this section in your edit page tab on the leftmenu. This will help users on the network find out more about your page";
	addslashes($pageabout);
}
$randstring = genRandomString();
//Connect to database
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);
//Create the page
$query = "INSERT INTO `page`(`admins`, `name`, `headline`, `about`, `picture`, `category`, `registered`, `right`) VALUES ('$uid','$pagename','$pagehead','$pageabout','$randstring','$pagecat','$pagedate','$pageright')";
$result = mysql_query($query, $mysql_link);
if (!$result) {
	//achtung!
	mysql_close($mysql_link);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/page/NewPage.php?e=wr">';
	exit;
}
//Get the page just created
$query2 = "SELECT `pid`, `name` FROM `page` WHERE `picture` = '$randstring'";
$result2 = mysql_query($query2,$mysql_link);
$row = mysql_fetch_array($result2);

$pid = $row['pid'];

//add new page to my CONTACTS
$get_contact = mysql_query("INSERT INTO `friends`(`user1`, `user2`, `type`) VALUES ('$uid', '$pid', '1')", $mysql_link);	//is he in your contacts

// post on the WALL
$line = "<div class=\"wall_user\">";
$line .= "New group page <strong><a href=\"/page/?id=".$pid."\">".$row['name']."</a></strong> joined UASocial.";
$now = time();
$author = 'p'.$pid;
$result = mysql_query("INSERT INTO `wall`(`uid`,`author`,`line`,`timestamp`,`broadcast`) VALUES ('$pid', '$author', '$line', '$now', '1') ", $mysql_link );


//Erase the rand string
$query3 = "UPDATE `page` SET `picture` = '0/page' WHERE `picture` = '$randstring'";
$result3 = mysql_query($query3,$mysql_link);

mysql_close($mysql_link);

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/page/?id='.$pid.'">';

?>
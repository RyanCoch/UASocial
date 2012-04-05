<?php
$a = session_id();
if(empty($a)) session_start();
include ('../top/header.php'); 
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

include_once(inner()."utils/functions.php");
include_once(inner()."utils/online.php");

?>

<div id="left" style="min-height:750px;">

<?php 
include(inner()."utils/leftmenu.php");
?>

</div> <!-- left -->

<div id="right" style="min-height:750px;">

<div id="profile">
<h2>Create New Page</h2>
<br />
<?php
$error = $_GET['e'];
if($error == 'pn'){
	echo "<p class=\"error\"> Page must have a name. </p>";
}
if($error == 'wr'){
	echo "<p class=\"error\"> Page can not be created at this time. Please try again later. </p>";
}


echo "<p style=\"color:#666; font-size:1em;\"><strong>$name</strong>, you about to create a new group page on UASocial! <br />You'll be it's administrator, you can always add more ";
echo "administrators later.<br />";
echo "Group pages are able to post <em>Official</em> and <em>Broadcast</em> messages on the social wall.<br />";
echo "Users of UASocial can subscribe to Page's updates by adding the page to contacts.<br />";
echo "<em>Broadcast</em> messages are visible to all users of UASocial.</p>";
?>
<div>
	<p>Please fill Out the Following form to create a <strong>Group Page</strong>.</p>
   <form method="post" enctype="multipart/form-data" name="page_form" action="makepage.php" style="margin-left:15px; font-size:1.1em;">    
        <label class="from">Name</label>
		<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
        <input type="text" name="name" maxlength="100" style="width:400px;" /> <br/>
        <label class="subject">Headline</label>
		<input type="text" name="headline"  maxlength="100" style="width:400px;" /> <br/>
        <label>Category</label>
        <select name="catdropdown" style="width:250px;">
        <option value="Student Group">Student Group</option>
		<option value="Sorority">Sorority</option>
		<option value="Fraternity">Fraternity</option>
        <option value="Website">Website</option>
        <option value="Entertainment">Entertainment</option>
        <option value="Service">Service</option>
        <option value="Product">Product</option>
        <option value="Organization">Organization</option>
		</select><br />
        <label>Posting</label>
        <select name="right" style="width:250px;">
		<option value="0">Only Admins can post on the wall</option>
		<option value="1">Everyone can post on the wall</option>
		</select><br />       
        
        <br />
        <label style="width:400px;" id="atext">About Page</label><br /><br />
		<textarea name="text" id="about" maxlength="1000" onkeyup="javascript:textarea(1000, 'About Page ');" style="width:500px;" rows="8"></textarea><br /><br />

        <button type="submit" value="Send">Create Page</button>
    </form>
</div>


</div> <!-- profile -->
</div> <!-- right -->

<?php
include (inner()."top/footer.php");
mysql_close($mysql_link);
?>



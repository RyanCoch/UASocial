<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/index.php">';
	exit;
}

	include ("top/header.php");

	$uid = $_SESSION['uid'];	//my info
	$byname = $_GET['byname'];
	$search_name = trim(strip_tags($_GET['search_name']));
	
	if (!$search_page) $search_page = "0";
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	include("utils/online.php");		// UPDATE ONLINE STATUS

	?>
        
    <div id="left" style="min-height:750px;">
        <!-- empty field -->
        
		<?php include("utils/leftmenu.php");?>

    
    </div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
    <h2>Search pages <span style="margin-left:15px;"><a href="/search.php" style="font-size:.7em; color:#999; text-decoration:underline;">Search profiles</a></span></h2>
    
    <?php
		if ($error == "notfound") {
			echo "<p class=\"error\">Your search did not match any group page.</p>";
			echo "<p>Please try another search.</p>";
			}
	?>
    
    <form method="get" action="/searchp.php" id="search_form">    
        <label>Page Name</label>
        <input class="tsearch" type="text" name="search_name" style="width:450px;" value="<?php echo $search_name;?>"/><input type="hidden" name="byname" value="1" />
        <br /><button type="submit" class="bsearch" value="search" style="float:right;">Search</button>    
    </form>
    

<?php
echo "<div style=\"clear:both;\"></div>";
	include_once ("utils/functions.php");

	if (!$byname && !$error) :
		
		$byname=0;
		$search_name=0;
		
		$query = "SELECT * FROM `page` ORDER BY `registered` DESC LIMIT 0, 10 "; // show just 10 matches

 	elseif ($byname && $search_name && !$error) :	
	
		$srch="%".$search_name."%"; 
		$query2 = " `name` LIKE '$srch' or `headline` LIKE '$srch' "; 
		$query = "SELECT * FROM `page` WHERE ";
		$query = $query.$query2." ORDER BY `registered` DESC LIMIT 0, 10 "; // show just 10 matches
	
	endif;
	
	if (!$error) :
	
		$result = mysql_query($query, $mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//cant find anybody
		 mysql_close($mysql_link);
		 echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=/searchp.php?error=notfound&byname=$byname&search_name=$search_name\">";
		 exit;
	 }
	 	 
	while($row = mysql_fetch_array($result)) {				//go through the dataset
	
		echo "<div class=\"uno\">\r\n";
		echo "<a href=\"/page/?id=".$row['pid']."\">";
		echo "<img align=\"left\" src=\"/page/pictures/".$row['picture']."60.jpg\" border=\"0\"></a>";
		echo "<p><a class=\"name\" href=\"/page/?id=".$row['pid']."\">";
		echo $row['name'];
		echo "</a>";
		echo "<br/>\r\n<strong>".$row['headline']."</strong>\r\n";
		echo "</p>\r\n";
		echo "</div>\r\n\r\n";

	}// WHILE LOOP
	echo "<div style=\"clear:both;\"></div>";
	
	endif;
	
	mysql_close($mysql_link);
	
		
 ?>
 
</div> <!-- profile -->
</div> <!-- right -->


<?php
include (inner()."top/footer.php");
?>

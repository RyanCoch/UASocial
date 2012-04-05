<?php
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

	include ("top/header2.php");
	
	$search_sex = $_GET['sex'];
	$search_age_from = $_GET['from'];
	$search_age_to = $_GET['to'];
	$search_page = $_GET['page'];
	$error = $_GET['error'];
	$display = $_GET['display'];
	if (!$display) {$display = "info";};
	
	$uid = $_SESSION['uid'];	//my info
	$name = $_SESSION['name'];
	$picture = $_SESSION['picture'];
	$ilook4 = $_SESSION['looking_for'];
	$ilook4age_from = $_SESSION['looking_for_age_from'];
	$ilook4age_to = $_SESSION['looking_for_age_to'];
	$mysex = $_SESSION['sex'];
	
	
	$byname = $_GET['byname'];
	$search_name = trim(strip_tags($_GET['search_name']));
	
	if (!$search_sex) $search_sex = $ilook4;
	if (!$search_age_from) $search_age_from = $ilook4age_from;
	if (!$search_age_to) $search_age_to = $ilook4age_to;
	
	if (!$search_page) $search_page = "0";
	
	$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
	mysql_select_db($mysql_database, $mysql_link);

	include("utils/online.php");		// UPDATE ONLINE STATUS

	?>
        
    <div id="left" style="min-height:750px;">
        <!-- empty field -->
        
		<?php include(inner()."utils/leftmenu.php");
		include_once(inner()."utils/functions.php");
		?>

    
    </div> <!-- left -->
    
    <div id="right" style="min-height:750px;">
	<div id="profile">
    <h2>Search profiles<span style="margin-left:15px;"><a href="/searchp.php" style="font-size:.7em; color:#999; text-decoration:underline;">Search pages</a></span></h2>
    
    
    
    <?php
		include_once(inner()."top/bar8ad.php");
		
		if ($error == "notfound") {
			echo "<p class=\"error\">Your search did not match any person.</p>";
			echo "<p>Please try another search.</p>";
			}
	?>
    
    <div id="form_header"></div>
    <form method="get" action="search.php" id="search_form" style="background-color:#f8f8f8; padding:0 20px 0 20px; margin:0px;">    
	<?php if (!$byname):?>
        <label for="sex">Sex</label>
        <select name="sex" class="tsearch">
        <option value="1" <?php if ($search_sex==1) echo "selected";?>>Boys</option>
        <option value="2" <?php if ($search_sex==2) echo "selected";?>>Girls</option>
        <option value="3" <?php if ($search_sex==3) echo "selected";?>>Everyone</option>
        </select>
        <br />
        <label for="agefr">Ages from</label>
        <input class="tsearch" type="text" name="from" size="10" value="<?php echo $search_age_from;?>"/>
        <label for="ageto" class="no"> to </label>
        <input class="tsearch" type="text" name="to" size="10" value="<?php echo $search_age_to;?>"/>
        
    <?php elseif ($byname): ?>    
        
        <label for="agefr">Name</label>
        <input class="tsearch" type="text" name="search_name" size="50" value="<?php echo $search_name;?>"/><br />
        <input type="hidden" name="byname" value="1" />
<br />
        
    <?php endif; ?>    
        
        <p style="margin:0;"><small><a href="search.php?sex=2&from=18&to=29"> Search for girls of age 18-29 </a></small> | <small><a href="search.php?sex=1&from=18&to=29"> Search for boys of age 18-29 </a></small> | <small><a href="search.php?byname=1"> Search by name</a></small></p>
        <center><button type="submit" class="bsearch" value="search">Search</button></center>
    </form>
    <div id="form_footer" style="margin-bottom:10px;"></div>
    

<?php

	if ($search_sex && $search_age_from && $search_age_to && !$error && !$byname) :
	//make search
	
	list($Y,$m,$d) = explode("-",date("Y-m-d"));	
	$enddate = $Y - $search_age_from;		//bigger than this
	$startdate = $Y - $search_age_to - 1;	//but smaller than this
	$enddate = $enddate."-".$m."-".$d;
	$startdate = $startdate."-".$m."-".$d;
	
	//extract all the data	
		
	$query2 = "";
	if ($search_sex != "3") {
		$query2 = " (sex='$search_sex') AND ";
	}
	$query2 .= "((dob >= '$startdate' AND dob <= '$enddate') OR (dob = '0000-00-00')) AND active > 0";

	//count number of persons to find the number of pages
	$query = "SELECT COUNT(name) FROM profile WHERE ".$query2; 
	$result = mysql_query($query, $mysql_link); 
	$row = mysql_fetch_row($result); 
	$total_records = $row[0];
	echo "<p style=\"margin:3px 0;\"><small>".$total_records." people found&nbsp;&nbsp;&nbsp;".
		"<a href=\"search.php?display=info&sex=$search_sex&from=$search_age_from&to=$search_age_to&page=$search_page\">show details</a>&nbsp;/&nbsp;<a href=\"search.php?display=picture&sex=$search_sex&from=$search_age_from&to=$search_age_to&page=$search_page\">show pictures</a>".
		"</small></p>\r\n";

	if ($total_records>$lines_per_page):
	$pages = ceil($total_records/$lines_per_page);
	//display pages
	
	$ista = 0;
	$iend = $pages;
	if ($pages>9) {
		$ista = $search_page - 4;
		$iend = $search_page + 4;
		if ($ista < 0) {
			$ista = 0;
			$iend = 9;
		}
		if ($iend > $pages) {
			$iend = $pages;
			$ista = $iend - 8;
		}
	}	
		
    $bar = "<div class=\"bar\"><p><strong>Pages: </strong>";
	if ($ista>0) $bar .= "... ";
			for ($i = $ista; $i < $iend; $i++) {
				$bar.= "<a href=\"search.php?sex=$search_sex&from=$search_age_from&to=$search_age_to&display=$display&page=$i\"".($search_page==$i?" class='active' ":"").">";
				$bar.= ($i+1);
				$bar.= "</a>\r\n";
			}
    if ($iend<$pages) $bar .= " ... ";
	$bar .= "</p></div>	\r\n";
	echo $bar;
	endif;
	
	
	$search_page = $search_page * $lines_per_page;
	$query = "SELECT profile.uid, profile.name, profile.sex, profile.dob, profile.dob_flag, profile.quad, profile.quad_flag, profile.picture, profile.look_for, profile.look_age_from, profile.look_age_to, online.time FROM profile, online WHERE ";
	$query = $query.$query2." AND profile.uid = online.uid ORDER BY last_login DESC LIMIT $search_page, $lines_per_page "; //split pages
	
	$result = mysql_query($query, $mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//cant find anybody
		 mysql_close($mysql_link);
		 echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=search.php?error=notfound&sex=$search_sex&from=$search_age_from&to=$search_age_to\">";
		 exit;
	 }
	 	 
	while($row = mysql_fetch_array($result)) {				//go through the dataset
		$persons_age = calculate_dob($row['dob']);
		$chemistry = 0;
		if (($ilook4 == 3 || $ilook4 == $row['sex'])&&($row['look_for'] == 3 || $row['look_for'] == $mysex)) {		
		//я ищу кого угодно, или его пол тот что я ищу, он ищет лицо моего пола
				if (($ilook4age_from<=$persons_age && $ilook4age_to>=$persons_age))
					$chemistry = 1;																			
			}
			
	if ($display=="info") {
		$online = "<span class=\"offline\">&nbsp;</span>";
		$user_time = $row['time'];
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			}
		$time_online = 0;
		$last_time = time() - $row['time'];
		if ($last_time <= 0) $time_online = "Now";
		if (!$time_online && $last_time<80) $time_online="Minute ago";
		if (!$time_online) $last_time = round($last_time/60);
		if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && $last_time<80) $time_online = "Hour ago";
		if (!$time_online) $last_time = round($last_time/60);
		if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "Yesterday";
		if (!$time_online) $last_time = round($last_time/24);
		if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && $last_time>=30 && $last_time<365) $time_online = " More than a month ago";
		if (!$time_online) $last_time = round($last_time/365);
		if (!$time_online && $last_time<40)  $time_online = strval($last_time). " year".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online) $time_online = "Never";
		
		echo "<div class=\"uno".(($chemistry==1)?" match":" not")."\">\r\n";
		echo "<a href=\"profile.php?id=".$row['uid']."\">";
		echo "<img align=\"left\" src=\"pictures/".$row['picture']."60.jpg\" border=\"0\"></a>";
		echo "<span class=\"last_time\">Last time online: ".$time_online."</span>";
		echo "<p><a class=\"name\" href=\"profile.php?id=".$row['uid']."\">";
		echo $row['name'];
		echo "</a>";
		if ($row['dob_flag']) {
			//echo "<br/>\r\nAge: <strong>".calculate_dob($row['dob'])."</strong>\r\n";
			echo ", <strong>".$persons_age."</strong>\r\n";
		}
		echo $online;
		if ($row['quad_flag']) {
			echo "<br/>\r\nResidence: <strong>".calculate_quad($row['quad'])."</strong>\r\n";
		}
		echo "<br/>\r\nLooking for: <strong>".calculate_look_for($row['look_for'])."</strong>";
		
		echo "</p>\r\n";
		echo "</div>\r\n\r\n";
	} else {
		// if display == picture	
		echo "<div class=\"duo".(($chemistry==1)?" match":" not")."\">\r\n";
		echo "<p><a class=\"name\" href=\"profile.php?id=".$row['uid']."\">";
		echo $row['name'];
		echo "</a>";
		if ($row['dob_flag']) {
			//echo "<br/>\r\nAge: <strong>".calculate_dob($row['dob'])."</strong>\r\n";
			echo ", <strong>".$persons_age."</strong>";
		}
		echo "</p>";
		echo "<a href=\"profile.php?id=".$row['uid']."\">";
		echo "<img align=\"left\" src=\"pictures/".$row['picture']."170.jpg\" border=\"0\"></a>";
		echo "</div>\r\n\r\n";
		}
		
	}// WHILE LOOP
	echo "<div style=\"clear:both;\"></div>";
	mysql_close($mysql_link);
	
	if ($total_records>$lines_per_page) echo $bar;
	
?>

 <?php 

 	elseif ($byname && $search_name && !$error) :
	
	include_once ("utils/functions.php");
	
	$srch="%".$search_name."%"; 
	$query2 = "(profile.name LIKE '$srch' OR profile.email LIKE '$srch') AND profile.active > 0"; 
	$query = "SELECT profile.uid, profile.name, profile.sex, profile.dob, profile.dob_flag, profile.quad, profile.quad_flag, profile.picture, profile.look_for, profile.look_age_from, profile.look_age_to, online.time FROM profile, online WHERE ";
	$query = $query.$query2." AND profile.uid = online.uid ORDER BY last_login DESC LIMIT 0, 10 "; // show just 10 matches
	
		$result = mysql_query($query, $mysql_link);
	if ( (!$result) || (!mysql_num_rows($result)) ) {
		//cant find anybody
		 mysql_close($mysql_link);
		 echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=search.php?error=notfound&byname=$byname&search_name=$search_name\">";
		 exit;
	 }
	 	 
	while($row = mysql_fetch_array($result)) {				//go through the dataset
		$persons_age = calculate_dob($row['dob']);
		$chemistry = 0;
		if (($ilook4 == 3 || $ilook4 == $row['sex'])&&($row['look_for'] == 3 || $row['look_for'] == $mysex)) {		
		//я ищу кого угодно, или его пол тот что я ищу, он ищет лицо моего пола
				if (($ilook4age_from<=$persons_age && $ilook4age_to>=$persons_age))
					$chemistry = 1;																			
			}

		$online = "<span class=\"offline\">&nbsp;</span>";
		$user_time = $row['time'];
		if (time()<=$user_time) {
			$online = "<span class=\"online\">&nbsp;</span>";
			}
		$time_online = 0;
		$last_time = time() - $row['time'];
		if ($last_time <= 0) $time_online = "Now";
		if (!$time_online && $last_time<80) $time_online="Minute ago";
		if (!$time_online) $last_time = round($last_time/60);
		if (!$time_online && $last_time<45) $time_online = strval($last_time)." minute".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && $last_time<80) $time_online = "Hour ago";
		if (!$time_online) $last_time = round($last_time/60);
		if (!$time_online && ($last_time<intval(date("H"))||($last_time<23))) $time_online = strval($last_time). " hour".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && ($last_time>=intval(date("H"))&&($last_time<23))) $time_online = "Yesterday";
		if (!$time_online) $last_time = round($last_time/24);
		if (!$time_online && $last_time < 30) $time_online = strval($last_time). " day".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online && $last_time>=30 && $last_time<365) $time_online = " More than a month ago";
		if (!$time_online) $last_time = round($last_time/365);
		if (!$time_online && $last_time<40)  $time_online = strval($last_time). " year".(($last_time%10==1)?"":"s")." ago";
		if (!$time_online) $time_online = "Never";
	
		echo "<div class=\"uno".(($chemistry==1)?" match":" not")."\">\r\n";
		echo "<a href=\"profile.php?id=".$row['uid']."\">";
		echo "<img align=\"left\" src=\"pictures/".$row['picture']."60.jpg\" border=\"0\"></a>";
		echo "<span class=\"last_time\">Last time online: ".$time_online."</span>";
		echo "<p><a class=\"name\" href=\"profile.php?id=".$row['uid']."\">";
		echo $row['name'];
		echo "</a>";
		if ($row['dob_flag']) {
			//echo "<br/>\r\nAge: <strong>".calculate_dob($row['dob'])."</strong>\r\n";
			echo ", <strong>".$persons_age."</strong>\r\n";
		}
		echo $online;
		if ($row['quad_flag']) {
			echo "<br/>\r\nResidence: <strong>".calculate_quad($row['quad'])."</strong>\r\n";
		}
		echo "<br/>\r\nLooking for: <strong>".calculate_look_for($row['look_for'])."</strong>";
		
		echo "</p>\r\n";
		echo "</div>\r\n\r\n";

	}// WHILE LOOP
	echo "<div style=\"clear:both;\"></div>";
	mysql_close($mysql_link);
	
	
	
	
	
	endif;
	
	
 ?>
 
</div> <!-- profile -->
</div> <!-- right -->


<?php
include ("footer.php");
?>

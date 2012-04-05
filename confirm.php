<?php 
$a = session_id();
if(empty($a)) session_start();

include("top/header2.php");

$uid = $_GET['uid'];
$key = $_GET['key'];

$activate = $_POST['activate'];
$sex = $_POST['sex'];	
	
if ((!$uid)OR(!$key)) {
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}
?>

<div id="left" style="height:700px;">
&nbsp;
</div>

<script type="text/javascript">
function act() {
	var tp = "user";
	$.post("utils/wpost.php", {type: tp, id: <?php echo $uid;?>});
}
</script>

<div id="right" style="height:700px;">

<div id="profile">

<?php

//open the connection
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

//check if such user exists and not active
  	 $result = mysql_query("SELECT uid, active, email, password, sex, picture FROM profile WHERE uid='$uid' AND active='0' ", $mysql_link) or die(mysql_error());
     if(!mysql_num_rows($result)) {
		 mysql_close($mysql_link);
		 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
		 exit;
	 }
	$row = mysql_fetch_array($result);
	
	//check if key is correct
	$pwd = $row['password'];
	$email = $row['email'];
	
	if (getKey($pwd,$email)!=$key) {
		mysql_close($mysql_link);
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
		exit;
	}
	
	if ($row['sex']=="1" or $row['sex']=="2") {
		$activate = "yes";
		$sex = $row['sex'];
		$dont_touch_picture = "1";
		$picture = $row['picture'];
	}
		
	if (!$activate) :
	
	?>
    
	<p><span style="font-size:1.5em; color:#999;">Activation</span></p>
    
    <p>By viewing or accessing UASocial.com website, and/or using any information from this website, you expressly agree to the <a href="terms.php">Terms and Conditions</a> detailed below.<br /><br />

	</p>

	<p><span style="font-size:2em; color:#999;">Who are you?</span></p>

	<script type="text/javascript">
	function magic(i) {
		if (i==1) {
			document.getElementById('sex2').checked = false;
			document.getElementById('sex3').checked = false;
			if (document.getElementById('sex1').checked)
				document.getElementById('btn').style.display = "inline";
				else
				document.getElementById('btn').style.display = "none";
			
		}
		if (i==2) {
			document.getElementById('sex1').checked = false;
			document.getElementById('sex3').checked = false;
			if (document.getElementById('sex2').checked)
				document.getElementById('btn').style.display = "inline";
				else
				document.getElementById('btn').style.display = "none";
			
		}
		if (i==3) {
			document.getElementById('sex1').checked = false;
			document.getElementById('sex2').checked = false;
			if (document.getElementById('sex3').checked)
				document.getElementById('btn').style.display = "inline";
				else
				document.getElementById('btn').style.display = "none";
			
		}
	}	
	</script>

	<form method="post" action="confirm.php?uid=<?php echo $uid;?>&key=<?php echo $key;?>">
    <input type="hidden" name="activate" value="yes" /><center>
    <table border="0px">
    <tr><td align="center"><img src="images/reg_man.jpg" width="167" height="247" alt="man" /><br />
    <input type="checkbox" id="sex1" name="sex" value="1" onchange="javascript:magic(1);" />
    </td><td align="center"><img src="images/reg_woman.jpg" width="153" height="247" alt="woman" /><br />
    <input type="checkbox" id="sex2" name="sex" value="2" onchange="javascript:magic(2);" /><br />
    </td><td align="center"><img src="images/reg_na.jpg" width="153" height="247" alt="na" /><br />
    <input type="checkbox" id="sex3" name="sex" value="0" onchange="javascript:magic(3);" /><br />
    </td></tr>
	</table><br />
<br />

    <button class="tsearch" type="submit" id="btn" name="submit" value="Activate!" style="display:none;" onclick="javascript:act();">Activate!</button>
    </center>
    </form>

<?php

	endif;

	if ($activate == "yes") :
	//set the sex
	if (!$dont_touch_picture) {
		if ($sex == "1")
			$picture="0/boy";
			else
			$picture="0/girl";
	}
	//put key as new value of active	
   	$result = mysql_query("UPDATE profile SET active = '$key', sex = '$sex', picture = '$picture' WHERE uid = '$uid'", $mysql_link)or die(mysql_error());

	if ($result) {
		mysql_close($mysql_link);
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=success_confirm.php">';
		exit;
	}

	mysql_close($mysql_link);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=register">';
	endif;

?>
</div>
</div>

<?php
include("footer.php");
?>
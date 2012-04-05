<?php
include("top/header2.php");
?>

<div id="left" style="height:700px;">
&nbsp;
</div>


<div id="right" style="height:700px;">

<div id="profile">

<?php 
$uid = $_GET['uid'];
$key = $_GET['key'];
$email = $_POST['email'];

if((!$email) AND (!$key)) :

?>

<p><span style="font-size:1.5em; color:#999;">Forgot password?</span></p>

<p>Please enter you email</p>
<form method="post" action="forgot.php">

<label  style="margin-left:10px;"  for="email">E-mail</label>
<input class="tsearch" type="text" name="email" value=""/><br />

<button class="bsearch" type="submit"  style="margin-left:10px;" value="submit">Submit</button><br />

<p><a href="index.php" class="fb">Not Registered?</a></p>

</form>&nbsp;

<?php elseif (($email)AND(!$uid)):
// if email needs to be send


  $email_exp = "^[A-Z0-9._%-]+@albany.edu$";
  if(!eregi($email_exp,$email)) {    
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=notalbany">';
	exit;
  }  


$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

   $query = "SELECT uid, active, email, name FROM profile ";
   $query .= "WHERE email='$email'";
   $result = mysql_query($query, $mysql_link);
   if (!$result) {
	   echo('Sorry, the database doesn\'t contain such email address.</div></div>');
	   include("footer.php");
	   exit;
   }
     if(mysql_num_rows($result)) {
		//if such record exists
		$row = mysql_fetch_array($result);
		mysql_close($mysql_link);
		
		if ($row['active'] == "0") {
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=active">';		//account is not active
				exit;
		}
	
		// send the restore email
		
		$email_to = $email;
		$token_link = $urladdress."/forgot.php?uid=".$row['uid']."&key=".$row['active'];
		$email_message = "Dear ".$row['name']."\r\n";
		$email_message .= "Someone, maybe you, asked to reset the password of the account associated with this email!\r\n";
		$email_message .= "If it wasn't you, please just ignore this message.\r\n";
		$email_message .= "To reset the password please follow this link:\r\n";
		$email_message .= $token_link;
		
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($email_to, $email_subject, $email_message, $headers); 
		
		// // send the email
		   echo('Please check your email.</div></div>');
		   include("footer.php");
		   exit;
	 } else { 
	 	echo('Sorry, the database doesn\'t contain such email address.');		
		 }
 ?>
 
<?php else :
// if password needs to be removed

//open the connection
$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

//check if such user exists
   $query = "SELECT uid, active, email, name FROM profile ";
   $query .= "WHERE uid='$uid' AND active='$key'" ;
   $result = mysql_query($query, $mysql_link);
   
   if (!mysql_num_rows($result)) {
	   mysql_close($mysql_link);
	   echo('Sorry, cannot access the database.</div></div>');
	   include("footer.php");
	   exit;
   }
   
	$row = mysql_fetch_array($result);
	
	//create new password
	$pwd = genRandomString();
	$md5 = md5($pwd);
	$new_act = rand(1000,10000);
	
	//change password AND KEY! in the database
	
	$query = "UPDATE profile SET password = '$md5', active = '$new_act' WHERE uid = '$uid' AND active = '$key'";
   	$result = mysql_query($query, $mysql_link);
	if (!$result) {
		//if it was unsuccessful
		mysql_close($mysql_link);
		echo('Sorry, cannot change database record.');
	}
	else {
		mysql_close($mysql_link);
		
		// send the email with new password
		
		$email_to = $row['email'];
		$token_link = $urladdress."/index.php?f=1";
		$email_message = "Dear ".$row['name']."\r\n";
		$email_message .= "Your password has been regenerated.\r\n";
		$email_message .= "Your new password is: ".$pwd."\r\n";
		$email_message .= "Please log in and change your password as soon as possible!\r\n";
		$email_message .= $token_link;
		
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($email_to, $email_subject, $email_message, $headers); 
		
		// // send the email
		
		echo('Your password has been changed! Please check your email.');
	}

endif; ?>

</div>
</div>

<?php
include("footer.php");
?>
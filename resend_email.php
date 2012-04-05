<?php 
$a = session_id();
if(empty($a)) session_start();

include ('top/header2.php');
?>

<div id="left" style="height:700px;">
&nbsp;
</div>


<div id="right" style="height:700px;">

<div id="profile">

<?php 
$email = $_POST['email'];
$password = $_POST['password'];

if((!$email) AND (!$password)) :

?>
<p><span style="font-size:1.5em; color:#999;">Activation link</span></p>

<form method="post" action="resend_email.php"  onsubmit="javascript:document.getElementById('password1').value = MD5(document.getElementById('password1').value);">

<label for="email">E-mail</label>
<input class="tsearch" type="text" name="email" value=""/><br />
<label for="password">Password</label>
<input class="tsearch" type="password" name="password" id="password1" value=""/><br />

<button type="submit" class="bsearch" width="282" height="83" value="resend">Send activation link</button><br />
<br>
<a href="index.php" class="fb" >Not Registered?</a>

</form>&nbsp;

<?php else:
// if username and password were inputed

  $email_exp = "^[A-Z0-9._%-]+@albany.edu$";
  if(!eregi($email_exp,$email)) {    
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?error=notalbany">';
	exit;
  }  


$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

   $query = "SELECT uid, email, password FROM profile ";
   $query .= "WHERE email='$email' AND password='$password'";
   $result = mysql_query($query, $mysql_link);
     if(mysql_num_rows($result)) {
		 //if such record exists
	$row = mysql_fetch_array($result);
 	mysql_close($mysql_link);

	// send the activation email
	
	$email_to = $email;
	$token_link = $urladdress."/confirm.php?uid=".$row['uid']."&key=".getKey($password,$email);
	$email_message = "Dear friend!\r\n";
	$email_message .= "\r\n";
	$email_message .= "To activate your account please follow the activation link:\r\n";
	$email_message .= $token_link;
	
	$headers = 'From: '.$email_from."\r\n".
	'Reply-To: '.$email_from."\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($email_to, $email_subject, $email_message, $headers); 
	
	// // send the email
		 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=success_reg.php">';
		 //header("Location: success_reg.php");
		 exit;
	 } else { 
	 	echo('Wrong email address or password.');		
		 }
 ?>
 
<?php endif; ?>

</div>
</div>

<?php
include("footer.php");
?>
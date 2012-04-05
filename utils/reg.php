<?php

include_once("settings.php");

$name=$_POST['name'];
$email=$_POST['email'];
$pwd=$_POST['password'];

  $email_exp = "^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$";
  if(!eregi($email_exp,$email)) {    
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=email">';
	exit;
  }  
  $email_exp = "^[A-Z0-9._%-]+@albany.edu$";
  if(!eregi($email_exp,$email)) {    
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=notalbany">';
		exit;
	  }  
	  
  $string_exp = "^[a-z .'-]+$";
  if(!eregi($string_exp,$name)) {
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=name">';
	exit;
  }
  if(strlen($pwd) < 6) {
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=short">';
	exit;
  }

$mysql_link = mysql_connect($mysql_host, $mysql_user, $mysql_password); 
mysql_select_db($mysql_database, $mysql_link);

//check if such user already exists
	$result = mysql_query("SELECT COUNT(uid) FROM profile WHERE email = '$email' ", $mysql_link);
	$r = mysql_fetch_row($result); 
	$users_with_email = $r[0];
	if ($users_with_email){
		 mysql_close($mysql_link);
		 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=user">';
		 exit;
	 }

if (($email) AND ($pwd) AND ($name)) {
		
	$today = date("Y-m-d");
 	$result = mysql_query("INSERT INTO `profile`(`email`, `name`, `password`, `active`, `registered`) VALUES ('$email', '$name', '$pwd', '0', '$today')", $mysql_link) or die(mysql_error());

   	$result = mysql_query("SELECT `uid`, `email`, `password` FROM `profile` WHERE `email` = '$email' AND `name` = '$name' AND `password` = '$pwd' ", $mysql_link); //get the uid
	$row = mysql_fetch_array($result);
	$uid = $row['uid'];

	$time = date("Y-m-d H:i:s");
	$query = "INSERT INTO `online`(`uid`, `time`) VALUES ('$uid','$time')";
	$result = mysql_query($query, $mysql_link);

	// send the activation email
	$email_to = $email;
	$token_link = $urladdress."/confirm.php?uid=".$uid."&key=".getKey($pwd,$email);
	$email_message = "Dear ".$name."\r\n";
	$email_message .= "We have successfully created your account!\r\n";
	$email_message .= "To complete your registration please follow this activation link:\r\n";
	$email_message .= $token_link;
	
	$headers = 'From: '.$email_from."\r\n".
	'Reply-To: '.$email_from."\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($email_to, $email_subject, $email_message, $headers); 
	
	//send welcome email
	$today = date("Y-m-d H:i:s");
	$adm = $adminid[1];
	$subject = "Welcome, ".$name."!<br>";
	$text = "<strong>Thanks for registering at UASocial!</strong><br/><br/>Please edit your personal information on <a href=\"settings.php\">settings</a> page, and dont forget to upload you profile picture today!<br/>";
	$text .= "We thank you for taking time to register. We have many features here that will enable you to meet new people around the university.";
	$text .= "This network is designed to make the social life easier at the univeristy.";
	$text .= "The features in which you can use to contact a fellow colleague are as following.";
	$text .= "Private Messages, Global Chat Room, Private Chat Rooms, Ability to add as many Contacts as you want, and much more!";
	$text = $subject."<br/>".$text;		  	
	$query = "INSERT INTO `message` (`from_uid`, `to_uid`, `didread`, `subject`, `text`, `type`, `disp_s`, `datetime`) VALUES  ('$adm','$uid','0','$subject','$text', '1', '0', '$today') ";
	$result = mysql_query($query, $mysql_link);
 	mysql_close($mysql_link);
	// // send the email
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../success_reg.php">';
	exit;
    } else {
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=../index.php?error=register">';
		exit;
   }
?>

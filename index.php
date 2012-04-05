<?php 
$a = session_id();
if(empty($a)) session_start();
if($_COOKIE['eml'] && $_COOKIE['pwd']){
include("verify.php");	
}
if(isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=wall.php">';
	exit;
}
include ('top/header2.php');
?>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '261776280532430', // App ID
      channelURL : '//www.uadndev.co.cc/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      oauth      : true, // enable OAuth 2.0
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
</script>


<div id="left" style="height:700px;">
&nbsp;
</div> <!-- left -->

<div id="right" style="height:700px; background:url(images/reg_background.jpg) no-repeat top left;">
<div id="window" style="height:460px;">

<?php
	$error = $_GET['error'];
	if ($error == "password") {
		echo "<p class=\"error\">Wrong username or password!</p>";
	}
	if ($error == "active") {
		echo "<p class=\"error\">Your account is not activated. <br/>Follow the link in your email.<br/><a href='resend_email.php'>Resend email.</a></p>";
	}
	if ($error == "register") {
		echo "<p class=\"error\">Cannot register new user at this time.</p>";
	}
	if ($error == "email") {
		echo "<p class=\"error\">The Email Address you entered does not appear to be valid.</p>";
	}
	if ($error == "name") {
		echo "<p class=\"error\">The Name you entered does not appear to be valid.</p>";
	}
	if ($error == "short") {
		echo "<p class=\"error\">Password cannot be shorter than 6 symbols.</p>";
	}
	if ($error == "user") {
		echo "<p class=\"error\">User with such email is already registered.</p>";
	}		
	if ($error == "notalbany") {
		echo "<p class=\"error\">You must have albany.edu email to register.</p>";
	}		
	if ($error == "welcome") {
		echo "<p class=\"error\">Incorrect welcome code.</p>";
	}		
	if ($error == "block") {
		echo "<p class=\"error\">Your account has been blocked.<br/ >You cannot access the network at this time.</p>";
	}		
	if ($error == "database") {
		echo "<p class=\"error\">Unexpected database error. Please try again later, or contact the administration.</p>";
	}		
?>

<div id="registration">
<img src="images/registration.png" width="191" height="30" /><br>
<h2>Registration</h2>

<form method="post" action="utils/reg.php" onsubmit="javascript:document.getElementById('password1').value = MD5(document.getElementById('password1').value);">

<label for="name">Your name</label><br />
<input class="tsearch" type="text" name="name" maxlength="50" style="width:200px; margin-top:5px;" value=""/><br />
<label for="email" style="margin-top:5px;">E-mail <small>(must be @albany.edu)</small></label><br />
<input class="tsearch" type="text" name="email" maxlength="50" style="width:200px; margin-top:5px;" value=""/><br />
<label for="password">Password</label><br />
<input class="tsearch" type="password" name="password" style="width:200px; margin-top:5px;" maxlength="50" id="password1" value=""/><br />

<button class="bsearch" style="margin-top:10px; margin-left:10px;" type="submit" name="image" width="282" height="83" value="register">Register</button>
<p><a href="javascript:login();" style="margin-left:10px; font-size:.9em;">Already registered?</a></p>
</form>&nbsp;
</div> <!-- registration -->

<div id="login" style="display:none;">
<img src="images/registration.png" width="191" height="30" /><br>
<h2>Log in</h2>
<form method="post" action="utils/auth.php" onsubmit="javascript:document.getElementById('password2').value = MD5(document.getElementById('password3').value);">

<label for="email">E-mail</label>
<input class="tsearch" type="text" maxlength="50" style="width:200px; margin-top:5px;" name="email" value=""/><br />
<label for="password" style="margin-top:5px;">Password</label>
<input type="hidden" name="password" id="password2" value=""/>
<input class="tsearch" type="password" maxlength="50" style="width:200px; margin-top:5px;" id="password3" value=""/><br />
Stay Logged In <input type="checkbox" name="rememberMe"  style="margin-top:5px;" /><br/>
<button class="bsearch" type="submit" name="image" width="282" height="83" value="register">Log in</button>
<p><a href="javascript:register();">Not Registered?</a><br />
<a href="forgot.php">Forgot the password?</a>
</p></form>
</div> <!-- login -->


</div> <!-- window -->

<?php 
include ('utils/news.php'); 
?>

</div> <!-- right -->

<?php 
include ('footer.php'); 
?>
<?php
$a = session_id();
if(empty($a)) session_start();
?>

<?php include ('top/header2.php');?>

<div id="left" style="height:700px;">
<!-- empty field -->
&nbsp;
</div> <!-- left -->


<div id="right" style="height:700px;">


<div id="contact">
<h2>Contact us</h2>
<br>

<p>To contact the developers of UASocial please use the form below. We strive to make this a great experience for our users. Please feel free to send us feedback as well using the following form.<br/>
<br/>

<span class="error">* Indicates a required field</span></p>

<?php
  $error = $_GET['error'];
 if($error == "email" || $error == "first" || $error == "last" || $error == "comments"){
	 echo "<p class=\"error\">Something is not valid in one of your fields. Try Again</p>";
	 }
?>
<form name="contactform" method="post" action="utils/send_form_email.php">
<label for="first_name" style="display:inline-block; width:120px;">First Name *</label>
<input type="text" class="tmail" name="first_name" maxlength="50" size="40" /><br />

<label for="last_name" style="display:inline-block; width:120px;">Last Name *</label>
<input type="text" class="tmail" name="last_name" maxlength="50" size="40" /><br />
<label for="email" style="display:inline-block; width:120px;">Email Address *</label>
<input type="text" class="tmail" name="email" maxlength="80" size="40" /><br /><br />

<label for="comments">Questions/Comments *</label><br /><br />
<textarea class="tmail" name="comments" maxlength="1000" style="width:540px;" rows="6"></textarea><br />
<button class="bmail" type="submit" value="Submit">Submit</button>
</form>
</div><!-- contact -->

</div><!-- right -->
<?php
include ("footer.php");
?>

<?php 
$a = session_id();
if(empty($a)) session_start();

include("top/header.php");
?>

<div id="left" style="height:700px;">
&nbsp;
</div>


<div id="right" style="height:700px;">

<div id="contact">
<p><span style="font-size:1.5em; color:#999;">Success!</span></p><br />

<p>Your account has been activated! Now you can use <strong>UASocial</strong>.</p>
<p>Please return to the main page to login: <a href="index.php?f=1">return</a>
</p>
</div>

<?php
	echo "<meta http-equiv=\"refresh\" content=\"3;url=/index.php?f=1\">";
?>

 
</div>

<?php
include("footer.php");
?>
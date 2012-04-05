<meta http-equiv="refresh" content="3;url=index.php">
<?php
$a = session_id();
if(empty($a)) session_start();
?>
<?php
include ("top/header2.php");
?>

<div id="left" style="height:700px;">
&nbsp;
</div>


<div id="right" style="height:700px;">

<div id="contact">
<h2>Success!</h2>
<p>Your question/comment has been sent succesfully. You will be redirected to the main page authomatically.</p>
<p>If redirection doesn't work, just follow this link: <a href="index.php">redirect</a>
</p>
</div>
 
</div>

<?php
include("footer.php");
?>
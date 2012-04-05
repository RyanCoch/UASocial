<?php 
$a = session_id();
if(empty($a)) session_start();

include("top/header2.php");
?>

<div id="left" style="height:700px;">
&nbsp;
</div>


<div id="right" style="height:700px;">

<div id="contact">
<h2>Success!</h2>
<p>Please check your email and follow the activation link in order to activate you account.</p>
<p>Please return to the main page: <a href="index.php">return</a>
</p>
</div>
 
</div>

<?php
include("footer.php");
?>
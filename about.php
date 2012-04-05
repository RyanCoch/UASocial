<?php
$a = session_id();
if(empty($a)) session_start();

include("top/header2.php");
?>

<div id="left" style="min-height:700px;">
 
</div>


<div id="right" style="min-height:700px;">
<div id="contact">
<img src="images/about.png" width="180" height="30" alt="about us" />

<p>
UASocial was developed for the sole purpose of University at Albany students and faculty. This network is designed to make the social life easier at the univeristy. This network has resitrictions for anyone without <em>albany.edu</em> email. Anyone without <em>albany.edu</em> email can not sign up.
</p>

<center><img src="images/pri_bg.jpg" width="457" height="175" alt="UASocial" />
</center>
  
</br><br />
<br />

<p align="right">
<span class="caption">Project Team</span><br /><br />
<span class="title">idea, programmer and design</span><br />
<span class="name">Mike Gordo <br /><a href="http://www.himorblog.com/robot/?page_id=80">uadn.himorblog.com</a></span><br /><br />
<span class="title">programmer and administration</span><br />
<span class="name">Ryan Cochrane</span><br />
<span class="title">PR and administration</span><br />
<span class="name">Matthew Grunert</span><br />
</p>

</div>
</div>

<?php 
include("footer.php");
?>
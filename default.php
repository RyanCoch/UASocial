<html>
<body style="background-color:#ffcc00;">

<table width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
<td valign="middle" align="center">


<div style="margin:0 auto; height:200px; width:201px; padding:0;">
<img src="images/construction.png" width="201" height="150">

    <?php
    $date1 = time();
	$date2 = mktime(0,0,0,12,1,2011);
	$dateDiff = $date2 - $date1;
	$fullDays = floor($dateDiff/(60*60*24));
	echo "<p>Grand opening in ".$fullDays." days.</p>"; 
    ?>    


</div></td>
</tr></table>
</body>
</html>
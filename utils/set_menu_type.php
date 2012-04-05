<?php
 session_start();
 $_SESSION['menu_type'] = $_POST['mnutype'];
 
 $referer = $_SERVER['HTTP_REFERER'];
 echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=$referer\">";
 exit;
 
?>

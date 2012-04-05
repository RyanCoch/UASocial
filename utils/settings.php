
<?php

//important constants

$mysql_host = "localhost";
$mysql_database = "uasocial_main";
$mysql_user = "uasocial_main";
$mysql_password = "password_here";
$urladdress = "http://www.uasocial.com";

function inner() {
	//  /home/uasocial/public_html
	$res = getcwd();
	$res = explode('/',$res);
	$res = '/'.$res[1].'/'.$res[2].'/'.$res[3].'/';	
	return $res;
}

$email_from = 'auth@uasocial.com';
$email_subject = "No reply - UASocial";

$number_of_admins = 3;
$adminid[0] = '1';
$adminid[1] = '2';
$adminid[2] = '6';

$lines_per_page = 10;
$messages_per_page = 20;
$msg_per_page = 25;

//important functions

function getKey($a, $b) {
	$s = "";
	$num = 0;
	for ($i = 0; $i < strlen($a); $i++) {
    	$num += 3*ord($a[$i]);
	}
	$s += $num;
	$num = 0;
	for ($i = 0; $i < strlen($b); $i++) {
    	$num += 2*ord($b[$i]);
	}
	$s += $num;
	return $s;
}

function genRandomString() {
    $length = 8;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

function addminutes($ctime,$const_time) {
	return $ctime + ($const_time*60);
}


?>
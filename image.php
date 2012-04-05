<?php

/*
 * IMAGE RESIZE ALGORITHM
 * MIKE GORDO 2009-2011
 */
$src =	$_GET['src'];
$type = $_GET['type'];

header('Content-type: image/jpeg');

$image = imagecreatefromjpeg($src);

if ($type==60) {
	$new_size_x = 60;
	$new_size_y = 60;
}
if ($type==30) {
	$new_size_x = 30;
	$new_size_y = 30;
}
if ($type==170) {
	$new_size_x = 170;
	$new_size_y = 220;
}

list($width,$height) = getimagesize($src);
$image_p = imagecreatetruecolor($new_size_x,$new_size_y);


	if ($width >= $height) { //horizontal
		$src_top = 0;
		$koef = $height/$new_size_y;
		$src_left = ($width-$new_size_x*$koef)/2;
		$src_h = $height;
		$src_w = $src_h*($new_size_x/$new_size_y);		
	}
	if ($width < $height) { //vertical
		$src_left = 0;
		$koef = $width/$new_size_x;
		$src_top = ($height-$new_size_y*$koef)/2;
		$src_w = $width;
		$src_h = $src_w*($new_size_y/$new_size_x);		
	}


imagecopyresampled($image_p,$image,0,0,$src_left,$src_top,$new_size_x,$new_size_y,$src_w,$src_h);
imagejpeg($image_p,null,100);
?>
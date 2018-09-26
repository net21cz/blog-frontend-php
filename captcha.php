<?php
header('Content-Type: image/jpeg');
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");

@session_start();

function generateCode($length, $possible = '23456789bcdfghkmnpqrstvwxyz') {
  $code = '';                                                              	
	for ($i = 0; $i < $length; $i++) { 
		$code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
	}
	return $code;
}
  
function cretateCaptcha($width, $height, $characters, $font_size, $suffix = 'def') {
  global $_SESSION;
  
	$code = generateCode($characters);

	$im = imagecreate($width, $height) or die('Cannot Initialize new GD image stream');
  
	$background_color = imagecolorallocate($im, 255, 255, 255);
	$colors = [
		imagecolorallocate($im, 0, 101, 126),
		imagecolorallocate($im, 255, 102, 153)
	];   

	$padding_left = ($width-($font_size/1.3 * $characters)) / 3;
	$padding_top = ($height-$font_size) / 2 + $font_size - 2;
  

	// dots	
	for ($i = 0; $i < ($width * $height) / 100; $i++) {
		imageellipse($im, mt_rand(0, $width), mt_rand(0, $height), mt_rand(1, 3), mt_rand(1, 3), $background_color);
	}
	for ($i = 0; $i < ($width * $height) / 8; $i++) {
		imageellipse($im, mt_rand(0, $width), mt_rand(0, $height), mt_rand(1, 3), mt_rand(1, 3), $colors[mt_rand(0, sizeof($colors) - 1)]);
	}  
	// code
	for ($i = 0; $i < strlen($code); $i++) {
		imagettftext($im, $font_size, 0, $padding_left + ($i * $font_size), $padding_top, $colors[mt_rand(0, sizeof($colors) - 1)], './captcha_font.ttf', $code[$i]);
	}

	imagejpeg($im);
	imagedestroy($im);
  
	$_SESSION['security_code_' . $suffix] = $code;    
}

$width = $_GET['width'] ? $_GET['width'] : '120';
$height = $_GET['height'] ? $_GET['height'] : '30';
$characters = $_GET['characters'] ? $_GET['characters'] : '4';
$font_size = $_GET['font_size'] ? $_GET['font_size'] : '20';
$suffix = $_GET['suffix'] ? $_GET['suffix'] : 'def';

cretateCaptcha($width, $height, $characters, $font_size, $suffix);
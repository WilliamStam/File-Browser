<?php
$cfg = array();
require_once('_include.php');
$return = array();
define( 'ROOT_DIR', $cfg['MEDIA'] );

$imgUrl = fixslashes(ROOT_DIR .  base64_decode($_GET["img_url"]));






header("Content-Type: image/jpeg");


$c = file_get_contents($imgUrl);
$arr = getimagesizefromstring($c);

$img = imagecreatefromstring($c);

if (!is_array($arr)) {
	//remote image is not available. Use default one.
	$c = file_get_contents("nobanner.jpg");
}

if (isset($_GET["width"])||isset($_GET['height'])){
	//Get Width and Height
	List($Width, $Height) = getimagesize($imgUrl);
	
	//Calc new Size
	$w = isset($_GET["width"])?$_GET["width"]:$Width;
	$h = $Height * ($w / $Width);
	
	if (isset($_GET['height'])&&$h>$_GET['height']){
		$h = $_GET['height'];
		$w = $Width * ($h / $Height);
		
		
	}
	
	
	//test_array(array($w,$h)); 
	
	
	
	
	
	//Build the image
	//Create new Base
	$NewImageBase = imagecreatetruecolor($w, $h);
	
	//copy image
	imagecopyresampled($NewImageBase, $img, 0, 0, 0, 0, $w, $h, $Width, $Height);
	$img = $NewImageBase;
}

imagejpeg($img);
?>
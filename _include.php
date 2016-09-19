<?php

date_default_timezone_set('Africa/Johannesburg');
setlocale(LC_ALL, 'en_ZA.UTF8');
$errorFolder = ".". DIRECTORY_SEPARATOR . "logs".DIRECTORY_SEPARATOR. "php";
if (!file_exists($errorFolder)) {
	@mkdir($errorFolder, 01777, true);
}
$errorFile = $errorFolder . DIRECTORY_SEPARATOR . date("Y-m") . ".log";
ini_set("error_log", $errorFile);
if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}



$cfg = array();

$cfg['GIT'] = array(
		'username'=>"",
		"password"=>"",
		"path"=>"github.com/WilliamStam/File-Browser",
		"branch"=>"master"
);

$cfg['MEDIA'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR. "media" . DIRECTORY_SEPARATOR;
$cfg['TEMP'] = "tmp";






if (file_exists("_config.inc.php")) {
	require_once('_config.inc.php');
}
$GLOBALS['CFG']= $cfg;


?>

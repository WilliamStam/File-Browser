<?php



require_once('vendor/autoload.php');
require_once('_include.php');
require_once('inc/template.php');



ob_start();

//echo "woof";

function fixslashes($item){
	$return = $item;
	$return = str_replace(array("\\","/","\\\\","//"),DIRECTORY_SEPARATOR,$return);
	$return = str_replace(array("\\","/","\\\\","//"),DIRECTORY_SEPARATOR,$return);
	
	
	return $return;
}
function pathfix($path){
	$path = fixslashes(str_replace(ROOT_DIR,"",$path));
	
	
	return $path;
}
function deleteDirectory($dirPath) {
	if (is_dir($dirPath)) {
		$objects = scandir($dirPath);
		foreach ($objects as $object) {
			if ($object != "." && $object !="..") {
				if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
					deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
				} else {
					unlink($dirPath . DIRECTORY_SEPARATOR . $object);
				}
			}
		}
		reset($objects);
		rmdir($dirPath);
	}
}

?>

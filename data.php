<?php
$cfg = array();
require_once('_include.php');
$return = array();
define( 'ROOT_DIR', $cfg['MEDIA'] );
function fileData($file,$option=""){
	$return = pathinfo($file);
	if ($option){
		$return = isset($return[$option])?($return[$option]):"";
	}
	return $return;
}
function fileIcon($file){
	$ext = fileData($file,"extension");
	$return = "fa-file-o";
	
	$icons = array(
			
			"xls"=>"fa-file-excel-o",
			"xlsx"=>"fa-file-excel-o",
			
			"pdf"=>"fa-file-pdf-o",
		
			"doc"=>"fa-file-word-o",
			"docx"=>"fa-file-word-o",
				
			"mp4"=>"fa-file-video-o",
			"avi"=>"fa-file-video-o",
		
			"txt"=>"fa-file-text-o",
		
			"jpg"=>"fa-file-image-o",
			"jpeg"=>"fa-file-image-o",
			"png"=>"fa-file-image-o",
			"gif"=>"fa-file-image-o",
		
			"php"=>"fa-file-code-o",
			"html"=>"fa-file-code-o",
			"css"=>"fa-file-code-o",
			"js"=>"fa-file-code-o",
		
			"ppt"=>"fa-file-powerpoint-o",
			"pptx"=>"fa-file-powerpoint-o",
			
			
	);
	
	$return = isset($icons[$ext])?$icons[$ext]:$return;
	
	
	
	return $return;
}
function fileitem($path,$file) {
	
	$fullpath = $path.DIRECTORY_SEPARATOR.$file;
	
	if (file_exists($fullpath)){
		return array(
				"name" => $file,
				"size" => file_size(filesize($fullpath)),
				"path" => pathfix($fullpath),
				"type" => fileData($fullpath,"extension"),
				"icon" => fileIcon($file)
		);
		
	} 
	
	
}

function tree($path,$index=0) {
	
	$path = fixslashes($path);
	$return = array();
	$exclude = array(
			".",
			"..",
			"error_log",
			"_notes"
	);
	
	if (is_dir($path)) {
		$contents = (scandir($path));
		natsort($contents);
		
		
		
		foreach ($contents as $item) {
			if (!in_array($item, $exclude)) {
				$p = $path . $item;
				$f = array();
				$key = dirname($p);
				
				if (is_dir($p)){
					$f['name'] = $item;
					$f['path'] = pathfix($p.DIRECTORY_SEPARATOR);;
					$f['files'] = array();
					$f['contents'] = tree($p.DIRECTORY_SEPARATOR,$index++);
					
					$return['folders'][] = $f;
				} else {
					
					$return['files'][] = fileitem(dirname($p),$item);
				}
				
				
				
				
				
				
			}
		}
		
	}
	
	if (!isset($return['folders']))$return['folders'] = array();
	if (!isset($return['files']))$return['files'] = array();
	
	
	return $return;
	
}
function settings($settings){
	$defaults = array(
		"view"=>"thumbs",
		"folder"=>DIRECTORY_SEPARATOR	
	);
	
	$s = $_SESSION["file-browser-settings"];
	$n = array();
	foreach ($settings as $k=>$v){
		$val = $v;
		if ($val==""){
			$val = isset($s[$k])?$s[$k]:"";
		}
		$n[$k] = $val;
	}
	$settings = array_merge($defaults,$n);
	$_SESSION["file-browser-settings"] = $settings;
	return $settings;
	
}


$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : "";



$media_folder = ROOT_DIR;
$folder = isset($_REQUEST['folder']) ? $_REQUEST['folder'] : "";
$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : "";

$folder = str_replace(array(".."),"",$folder);
$folder = fixslashes($folder .DIRECTORY_SEPARATOR);
$folder = file_exists($media_folder.$folder)?$folder:"";


$settings = settings(array(
	"folder"=>$folder,
	"view"=>$view,
));


$settings['view'] = $settings['view']?$settings['view']:"thumbs";
//test_array(array($_SESSION["file-browser-settings"],$settings)); 






$settings['file'] = file_exists($media_folder.$folder.$file)?$file:"";
$settings['folder_name'] = basename($media_folder.$folder);

//test_array(fixslashes($media_folder.$folder.$file)); 








$current_folder  = fixslashes(($media_folder . DIRECTORY_SEPARATOR. $folder));

$parent_folder = dirname($current_folder);



//str_replace($cfg['MEDIA'], "",$parent_folder);
$breadcrumbs = array();

$breadcrumbs[] = array(
		"label"=>'<i class="fa fa-home"></i>',
		"path"=>DIRECTORY_SEPARATOR
);
$parts = explode(DIRECTORY_SEPARATOR,pathfix($current_folder));

$part_path = "";
foreach ($parts as $part){
	if ($part){
		$part_path = fixslashes($part_path . $part. DIRECTORY_SEPARATOR);
		$breadcrumbs[] = array(
				"label"=>$part,
				"path"=>$part_path
		);
	}
	
	
}








//test_array(tree($media_folder));
$return['controls'] = array();
$return['controls']['current'] = pathfix($current_folder);
$return['controls']['breadcrumbs'] = $breadcrumbs;
$return['settings'] = $settings;



if ($current_folder != fixslashes($media_folder)){
	$return['controls']['parent'] = fixslashes(str_replace($cfg['MEDIA'], "", dirname($current_folder).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);;
}



$return['folder'] = tree($media_folder . DIRECTORY_SEPARATOR. $folder);
$return['folders'] = tree($media_folder);


test_array($return);

?>

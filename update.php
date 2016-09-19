<?php

$cfg = array();
require_once('_include.php');
$return = array();

$GLOBALS['CFG'] = $cfg;
define('TIME_LIMIT', 30);

function replaceSensitive($str) {
	$cfg = $GLOBALS['CFG'];
	$replace = array(
			$cfg['GIT']['username'],
			$cfg['GIT']['password'],
			
	);
	
	foreach($replace as $item){
		$str = str_replace($item,str_repeat('*', strlen($item)),$str);
	}
	
	
	return $str;
}
$root_folder = (dirname(__FILE__));
chdir($root_folder);
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<title>Update Script</title>
	<link rel="stylesheet" href="/ui/style.css">
	<style>
		.error { color: #c33; }
		.prompt { color: #6be234; }
		.command { color: #888888; }
		.output { color: #999; }
		.panel-body:empty {
			display:none;
		}
	</style>
</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">
		Checking the environment
	</div>
	<div class="panel-body">
		
		
		
		<div>Running as <b><?php echo trim(shell_exec('whoami')); ?></b>.</div>
		

	<?php
	$requiredBinaries = array('git', 'composer --no-ansi');
	foreach ($requiredBinaries as $command) {
		$path = trim(shell_exec('which '.$command));
		echo $path;
		if ($path == '') {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			die(sprintf('<div class="error"><b>%s</b> not available. It needs to be installed on the server for this script to work.</div>', $command));
		} else {
			$version = explode("\n", shell_exec($command.' --version'));
			printf('<div><span class="command">%s</span> : <span class="output"> %s</span></div>'."\n" , $path , $version[0]);
		}
	}
	
	
	?>

	</div>
	<div class="panel-footer">
		Deploying from <span class="command"><?php echo $cfg['GIT']['path']; ?></span> branch 
		<span class="command"><?php echo $cfg['GIT']['branch']."\n"; ?></span>
	
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading">
		Environment OK
	</div>

</div>

<h3>Starting Commands</h3>



<?php
// The commands
$commands = array();
$root_folder = (dirname(__FILE__));


chdir($root_folder);
$return = "";
if (!file_exists($root_folder."\\.git")) {
	$commands[] = ('git init');
} else {
	
	//shell_exec('git stash');
	//$commands[] = ('git reset --hard HEAD');
}
if (!isLocal()){
	$commands[] = 'git pull https://'.$cfg['GIT']['username'] .':'.$cfg['GIT']['password'] .'@'.$cfg['GIT']['path'] .' ' . $cfg['GIT']['branch'];
}


$commands[] = "git submodule update --init --recursive";
$commands[] = "composer self-update";
$commands[] = "composer install";

$output = '';
foreach ($commands as $command) {
	set_time_limit(TIME_LIMIT); // Reset the time limit for each command
	$tmp = array();
	exec($command . ' 2>&1', $tmp, $return_code); // Execute the command
// Output the result
	printf('
<div class="panel panel-default"><div class="panel-heading">%s</div><div class="panel-body output">%s</div></div>
'
			, replaceSensitive(htmlentities(trim($command)))
			, replaceSensitive(htmlentities(trim(implode("\n", $tmp))))
	);
	$output .= ob_get_contents();
	ob_flush(); // Try to output everything as it happens
	
	if ($return_code !== 0) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', TRUE, 500);
		printf('
<div class="error">
	Error encountered! 
</div>
	');
		
		$error = sprintf(
				'Deployment error on %s using %s!'
				, $_SERVER['HTTP_HOST']
				, __FILE__
		);
		error_log($error);
		
		break;
		
		
		
	}
		
		
		
		
}


?>
	

	
	

</body>
</html>
<?php

$cfg = array();
require_once('_include.php');
$return = array();

define('TIME_LIMIT', 30);


ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<title>Simple PHP Git deploy script</title>
	<link rel="stylesheet" href="/ui/style.css">
</head>
<body>


<h1>Checking the environment ...</h1>


<div>Running as <b><?php echo trim(shell_exec('whoami')); ?></b>.</div>

<pre>
	<?php
	$requiredBinaries = array('git', 'composer --no-ansi');
	
	
	
	
	$path = trim(shell_exec('which git'));
	foreach ($requiredBinaries as $command) {
		$path = trim(shell_exec('which '.$command));
		if ($path == '') {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			die(sprintf('<div class="error"><b>%s</b> not available. It needs to be installed on the server for this script to work.</div>', $command));
		} else {
			$version = explode("\n", shell_exec($command.' --version'));
			printf('<b>%s</b> : %s'."\n" , $path , $version[0]
			);
		}
	}
	
	
	?>
	</pre>
<h2 class="text-success">Environment OK.</h2>
<div>
	Deploying from <pre><?php echo $cfg['GIT']['path']; ?> <?php echo $cfg['GIT']['branch']."\n"; ?></pre>
</div>

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


//$commands[] = 'git pull https://'.$cfg['GIT']['username'] .':'.$cfg['GIT']['password'] .'@'.$cfg['GIT']['path'] .' ' . $cfg['GIT']['branch'];
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
<span class="prompt">$</span> <span class="command">%s</span>
<div class="output">%s</div>
'
			, htmlentities(trim($command))
			, htmlentities(trim(implode("\n", $tmp)))
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
<?php
$path = isset($_REQUEST['path'])?$_REQUEST['path']:"";
$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
$new = isset($_REQUEST['new'])?$_REQUEST['new']:"";



$class = isset($_REQUEST['class'])?$_REQUEST['class']:"";
$method = isset($_REQUEST['method'])?$_REQUEST['method']:"";

if (!file_exists("class.{$class}.php")){
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	exit();
}

include_once("class.{$class}.php");

$class = new $class();
$class->path = $path;
$class->name = $name;
$class->new = $new;

$do = $class->$method();






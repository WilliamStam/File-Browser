<?php
require_once('_system.php');



$tmpl = new \template("template.twig","ui");
$tmpl->page = array(
		"section"    => "home",
		"sub_section"=> "home",
		"template"   => "home",
		"meta"       => array(
				"title"=> "File Browser",
		),
);
$tmpl->output();

?>

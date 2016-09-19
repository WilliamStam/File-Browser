<?php

class folder {
	private $vars = array();
	function __construct() {
		$cfg = array();
		require_once('_include.php');
		define( 'ROOT_DIR', $cfg['MEDIA'] );
		
		
		
		
	}
	
	public function __get($name) {
		return isset($this->vars[$name])?$this->vars[$name]:"";
	}
	
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	function fixVars(){
		$this->vars['folder'] = $this->vars['path'];
		$this->vars['path'] = ROOT_DIR . dirname($this->vars['path']) . DIRECTORY_SEPARATOR;
		
	}
	
	function _delete(){
		$return = array(
				"path"=>$this->vars['path'],
				"name"=>$this->vars['name'],
		);
		$this->fixVars();
		$errors = false;
		if (!file_exists($this->vars['path'].$this->vars['new'])){
			$errors = "Folder doesn't exists";
		}
		
		
		if ($errors===false){
			
			$return['result'] = deleteDirectory($this->vars['path'].$this->vars['name']);
			
			//$return['result'] = rename ($this->vars['path'].$this->vars['name'], $this->vars['path'].$this->vars['new']);
		} else {
			$return['error'] = $errors;
		}
		
		
		
		
		
		test_array($return);
	}
	function _rename(){
		$return = array(
				"path"=>$this->vars['path'],
				"name"=>$this->vars['name'],
				"new"=>$this->vars['new'],
		);
		$this->fixVars();
		$errors = false;
		
		if (file_exists($this->vars['path'].$this->vars['new'])){
			
			$errors = "Folder already exists";
		}
		
		
		if ($errors===false){
			
			
			
			$return['result'] = rename ($this->vars['path'].$this->vars['name'], $this->vars['path'].$this->vars['new']);
		} else {
			$return['error'] = $errors;
		}
		
		
		
		
		
		test_array($return);
		
	}
	function _new(){
		$return = array(
				"path"=>$this->vars['path'],
				"new"=>$this->vars['new'],
		);
		$this->fixVars();
		$errors = false;
		
		if (file_exists($this->vars['path'].$this->vars['new'])){
			
			$errors = "Folder already exists";
		}
		
		
		if ($errors===false){
			
			
			
			$return['result'] = mkdir($this->vars['path'].$this->vars['new'], 01777, true);
		} else {
			$return['error'] = $errors;
		}
		
		
		
		
		
		test_array($return);
		
	}
	
	
	
}


<?php

class file {
	private $vars = array();
	function __construct() {
		$cfg = array();
		require_once('_system.php');
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
		$this->vars['path'] = ROOT_DIR . ($this->vars['path']) ;
		
	}
	function _delete(){
		$return = array(
				"path"=>$this->vars['path'],
				"name"=>$this->vars['name'],
		);
		$this->fixVars();
		$errors = false;
		if (!file_exists($this->vars['path'].$this->vars['new'])){
			$errors = "File doesnt exists";
		}
		
		
		if ($errors===false){
			
			$return['result'] = unlink($this->vars['path'].$this->vars['name']);
			
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
			
			$errors = "File already exists";
		}
		
		
		if ($errors===false){
			
			
			
			$return['result'] = rename ($this->vars['path'].$this->vars['name'], $this->vars['path'].$this->vars['new']);
		} else {
			$return['error'] = $errors;
		}
		
		
		
		
		
		test_array($return); 
		
	}
	
	
	
}


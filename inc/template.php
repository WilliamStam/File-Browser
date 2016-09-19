<?php
Twig_Autoloader::register();

class template {
	private $config = array(), $vars = array();

	function __construct($template, $folder = "ui/", $strictfolder = false) {
		
		
		$this->cfg = ($GLOBALS['CFG']); 
		$this->config['cache_dir'] = $this->cfg['TEMP'];
		//$this->config['cache_dir'] = false;

		//test_array($this->cfg); 
		
		$this->vars['folder'] = $folder;
		$this->config['strictfolder'] = $strictfolder;

		$this->template = $template;

		




	}

	function __destruct() {
		$page = $this->template;
		
	}

	public function __get($name) {
		return $this->vars[$name];
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}

	private function default_vars() {

		$curPageFull = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$curPage = explode("?", $curPageFull);
		
		
			
		
		
		
		$this->vars['_isLocal'] = isLocal();
	



		
		$this->vars['_folder'] = $this->vars['folder'];
		$this->vars['_domain'] = "//".$_SERVER['HTTP_HOST'];

		

		$this->vars['_isAjax'] = is_ajax();




		

	}


	public function load() {


		return $this->render_template();
	}

	public function render_template() {
		$this->default_vars();
		if (is_array($this->vars['folder'])) {
			$folder = $this->vars['folder'];
		} else {
			$folder = array(
				$this->vars['folder']
			);
		}


		if (isset($this->vars['page'])) {
			
			if (isset($this->vars['page']['js']) &&$this->vars['page']['js'] !='' ){
				if (!is_array($this->vars['page']['js'])){
					$this->vars['page']['js'] = explode(",",$this->vars['page']['js']);
				} 
			}
			
			if (isset($this->vars['page']['template'])) {

				$folders = $folder;
				$tfile = $this->vars['page']['template'];
				$tfile = explode(".", $tfile);
				$tfile = $tfile[0];
				
				$version = "1";
				//test_array(array($folders,$tfile)); 

				foreach ($folders as $f) {
				//	test_array(array($f,$tfile));
					if (file_exists('' . $f . '/' . $tfile . '.twig')) {
					
						if (file_exists('' . $f . '/_js/' . $tfile . '.js')) {
							$this->vars['page']['template_js'] = '/' . $f . '/_js/' . $tfile . '.js';
						}
						if (file_exists('' . $f . '/_css/' . $tfile . '.css')) {
							$this->vars['page']['template_css'] = '/' . $f . '/_css/' . $tfile . '.css';
						}
						if (file_exists('' . $f . '/template/' . $tfile . '.jtmpl')) {
							$this->vars['page']['template_jtmpl'] = '/' . 'template/' . $tfile . '.jtmpl';
						}
						break;


					}
				}


				$this->vars['page']['template'] = $this->vars['page']['template'] . ".twig";
				
			}
		//	test_array($this->vars['page']);
		}

//test_array($this->vars['page']); 

		if ($this->config['strictfolder']) {
			$folder = $this->vars['folder'];
		}

		$loader = new Twig_Loader_Filesystem($folder);

		$options = array();
		$options['debug'] = true;
		//$options['cache'] = false;




		//test_array($this->vars); 



		$twig = new Twig_Environment($loader, $options);
		$twig->addExtension(new Twig_Extension_Debug());

		$twig->addFilter(new Twig_SimpleFilter('toAscii', function ($string) {
			$string = toAscii($string);
			return ($string);
		}
		));


		//test_array(array("template"=>$this->template,"vars"=>$this->vars));

		return $twig->render($this->template, $this->vars);


	}

	public function render_string() {
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		return $twig->render($this->vars['template'], $this->vars);
	}


	public function output($includetimer=true,$output=true) {
		
		
		$return = $this->load();
		if ($output){
			echo $return;
		} else {
			return $return;
		}
		

	}

	

}

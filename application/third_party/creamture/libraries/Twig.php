<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Twig
{
	private $CI;
	private $_twig;
	private $_template_dir;
	private $_cache_dir;
	private $_ext;

	/**
	 * Constructor
	 *
	 */
	function __construct($debug = false)
	{
		$this->CI =& get_instance();
		$this->CI->config->load('twig');
				
		log_message('debug', "Twig Autoloader Loaded");

		Twig_Autoloader::register();

		//HMVC patch by sevir
		if ($this->CI->router->fetch_module())
			$this->_template_dir[] = APPPATH.'modules/'.$this->CI->router->fetch_module().'/views/';
		$this->_template_dir[]= $this->CI->config->item('template_dir');
		//end HMVC patch 

		
		$this->_ext = $this->CI->config->item('views_extension');
		$this->_cache_dir = $this->CI->config->item('cache_dir');

		$loader = new Twig_Loader_Filesystem($this->_template_dir);

		$this->_twig = new Twig_Environment($loader, array(
               		 'cache' => $this->_cache_dir,
               		 'debug' => $debug,
		));
		
	       	foreach(get_defined_functions() as $functions) {
	            		foreach($functions as $function) {
	                		$this->_twig->addFunction($function, new Twig_Function_Function($function));
	            		}
        		}
        		$this->add_function('array');
	}

	public function add_function($name) 
	{
		$this->_twig->addFunction($name, new Twig_Function_Function($name));
	}

	public function register_extension($name)
	{
		$this->_twig->addExtension(new $name());
		return $this;
	}

	public function render($template, $data = array()) 
	{
		$template = $this->_twig->loadTemplate($template.$this->_ext);
		return $template->render($data);
	}

	public function display($template, $data = array()) 
	{
		$template = $this->_twig->loadTemplate($template.$this->_ext);
		/* elapsed_time and memory_usage */
		$data['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
		$data['memory_usage'] = $memory;
		$template->display($data);
	}
}
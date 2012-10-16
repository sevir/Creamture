<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Twig
{
	private $CI;
	private $_twig;
	private $_template_dir;
	private $_cache_dir;
	private $_debug;

	/**
	 * Constructor
	 *
	 */
	function __construct($params = array())
	{
		$this->_debug = (isset($params['debug'])?$params['debug']:FALSE);
		$template_dir = (isset($params['template_dir'])?$params['template_dir']:FALSE);

		$this->CI =& get_instance();
		$this->CI->load->config('twig');

		ini_set('include_path',
		ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'third_party/twig/lib/Twig');
		require_once (string) "Autoloader" . EXT;

		log_message('debug', "Twig Autoloader Loaded");

		Twig_Autoloader::register();

		if ($template_dir){
			$this->_template_dir = $template_dir;
		}else{
			$this->_template_dir = $this->CI->config->item('template_dir');
		}

		$this->_cache_dir = $this->CI->config->item('cache_dir');

		$loader = new Twig_Loader_Filesystem($this->_template_dir);

		$this->_twig = new Twig_Environment($loader, array(
                'cache' => $this->_cache_dir,
                'debug' => $this->_debug,
		));
		if(function_exists('gettext'))
			$this->_twig->addExtension(new Twig_Extensions_Extension_I18n());

		// Soporte de funciones nativas de PHP
		$this->add_function('array');

		// Soporte de la librería assets
		$this->CI->load->spark('assets/1.5.1');
		$this->add_function('assets_css');		
		$this->add_function('assets_js');
		$this->add_function('assets_css_group');		
		$this->add_function('assets_js_group');

		// Soporte de HMVC
		require_once APPPATH . 'third_party/twig/helpers/extensions_helper.php';
		$this->add_function('modules_run');
	}

	public function generate_gettext($tmpDir){
		$tplDir = $this->_template_dir;
		$loader = new Twig_Loader_Filesystem($tplDir);

		// force auto-reload to always have the latest version of the template
		$twig = new Twig_Environment($loader, array(
		    'cache' => $tmpDir,
		    'auto_reload' => true
		));
		$twig->addExtension(new Twig_Extensions_Extension_I18n());
		// configure Twig the way you want

		// iterate over all your templates
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tplDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
		{
		  // force compilation
		  $twig->loadTemplate(str_replace($tplDir.'/', '', $file));
		}
	}
	
	public function add_function($name)
	{
		$this->_twig->addFunction($name, new Twig_Function_Function($name));
	}

	public function render($template, $data = array())
	{
		$template = $this->_twig->loadTemplate($template);
		return $template->render($data);
	}

	public function render_string($string, $data = array())
	{
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader, array(
			'cache' => $this->_cache_dir,
			'debug' => $this->_debug,
		));

		if(function_exists('gettext'))
		{
			$twig->addExtension(new Twig_Extensions_Extension_I18n());
		}

		$twig->addFunction('array', new Twig_Function_Function('array'));
		$twig->addFunction('assets_css_group', new Twig_Function_Function('assets_css_group'));
		$twig->addFunction('assets_js_group', new Twig_Function_Function('assets_js_group'));
		$twig->addFunction('modules_run', new Twig_Function_Function('modules_run'));

		return $twig->render($string, $data);
	}

	public function display($template, $data = array())
	{
		$template = $this->_twig->loadTemplate($template);
		/* elapsed_time and memory_usage */
		$data['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
		$memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
		$data['memory_usage'] = $memory;
		$template->display($data);
	}

}
/* EOF */

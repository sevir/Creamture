<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class TWIG_Controller extends MX_Controller{
	// protected $twig_debug;
	protected $assets_path;

	public function __construct(){
		parent::__construct();
		$this->load->spark('twiggy/0.8.5');

		$this->load->spark('assets/1.5.1');
		if (file_exists(APPPATH.'modules/'.strtolower(get_called_class()).'/assets')){
			$this->load->config('assets');
			$assets_config = $this->config->item('assets');

			$this->assets_path = array(
				'js'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['js_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/js')
					).'/',
				'css'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['css_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/css')
					).'/',
				'img'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['img_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/img')
					).'/',
			);
		}


		//Global extended Twig
		if (function_exists('gettext'))
			$this->twiggy->register_extension('Twig_Extensions_Extension_I18n');
		$this->twiggy->register_function('assets_css');
		$this->twiggy->register_function('assets_js');
		$this->twiggy->register_function('assets_css_group');
		$this->twiggy->register_function('assets_js_group');
	}

	protected function display($template, $data = array()){
		if (is_array($this->assets_path)){
			$data['module_assets'] = $this->assets_path;
		}
		$this->twiggy->set($data)->template($template)->display();
	}

	protected function response($data){
		$this->output->set_header('Content-type: text/json');
		echo json_encode($data);
		return;
	}

	protected function register_function($fname){
		$this->twiggy->register_function($fname);
	}

	protected function register_filter($fname){
		$this->twiggy->register_filter($fname);
	}	

	private function getRelativePath($from, $to)
	{
	   $from = explode('/', $from);
	   $to = explode('/', $to);
	   foreach($from as $depth => $dir)
	   {

	        if(isset($to[$depth]))
	        {
	            if($dir === $to[$depth])
	            {
	               unset($to[$depth]);
	               unset($from[$depth]);
	            }
	            else
	            {
	               break;
	            }
	        }
	    }
	    //$rawresult = implode('/', $to);
	    for($i=0;$i<count($from);$i++)
	    {
	        array_unshift($to,'..');
	    }
	    $result = implode('/', $to);
	    return $result;
	}
}
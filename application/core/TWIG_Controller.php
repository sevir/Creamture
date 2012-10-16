<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class TWIG_Controller extends MX_Controller{
	protected $twig_debug;
	protected $assets_path;

	public function __construct(){
		parent::__construct();

		$this->load->add_package_path(APPPATH.'third_party/twig/');

		if(empty($this->twig_debug))
			$this->twig_debug = TRUE;

		if (file_exists(APPPATH.'modules/'.strtolower(get_called_class()).'/views')){
			$this->load->config('twig');
			if (is_array($this->config->item('template_dir'))){
				$this->config->set_item('template_dir', array_unshift(
					$this->config->item('template_dir'), 
					APPPATH.'modules/'.strtolower(get_called_class()).'/views'
					)
				);
			}else{
				$this->config->set_item('template_dir', array(
					APPPATH.'modules/'.strtolower(get_called_class()).'/views',
					$this->config->item('template_dir')
				));
			}	
			
			$this->load->spark('assets/1.5.1');
			$this->load->config('assets');
			$assets_config = $this->config->item('assets');
			$this->assets_path = array(
				'js'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['js_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/js')
					),
				'css'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['css_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/css')
					),
				'img'=>$this->getRelativePath(
					realpath(PUBLICPATH.$assets_config['assets_dir'].'/'.$assets_config['img_dir']),
					realpath(APPPATH.'modules/'.strtolower(get_called_class()).'/assets/img')
					),
			);
		}		

		$this->load->library('twig', array('debug'=>$this->twig_debug));
	}

	protected function display($template, $data = array()){
		if (is_array($this->assets_path))
			$this->twig->display($template, array_merge($data, array('module_assets'=>$this->assets_path)));
		else
			$this->twig->display($template, $data);
	}

	protected function getRelativePath($from, $to)
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
	    for($i=0;$i<count($from)-1;$i++)
	    {
	        array_unshift($to,'..');
	    }
	    $result = implode('/', $to);
	    return $result;
	}
}
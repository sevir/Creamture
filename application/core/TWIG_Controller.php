<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class TWIG_Controller extends MX_Controller{
	protected $twig_debug;

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
		}

		$this->load->library('twig', array('debug'=>$this->twig_debug));
	}
}
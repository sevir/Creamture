<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class TWIG_Controller extends MX_Controller{
	protected $views_path;
	protected $twig_debug;

	public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/twig/');

		if(empty($this->views_path))
			$this->views_path = APPPATH.'views';
		if(empty($this->twig_debug))
			$this->twig_debug = TRUE;
		$this->load->library('twig', array('debug'=>$this->twig_debug, 'template_dir'=>$this->views_path));
	}
}
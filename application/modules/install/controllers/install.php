<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends TWIG_Controller {
	private $assets;

	var $twig_debug = TRUE;

	function __construct()
    {
        parent::__construct();

		$this->load->library('session');

		$this->assets = array(
			'js'=>'../../../application/modules/install/assets/js/',
			'css'=>'../../../application/modules/install/assets/css/'
		);
		$this->load->helper(array('form','url','install'));
		$this->twig->add_function('getActualSection');
    }

	public function index()
	{
		redirect('install/features');
	}

	public function features(){
		$this->load->spark('assets/1.5.1');
		$this->twig->display('install_view.twig', array(
			'assets'=>$this->assets,
    		'img_path'=>auto_link($this->config->item('index_page').'/../../install/img/get/'),
    		'install_path'=>auto_link($this->config->item('index_page').'/../../install')
		) );
	}

	public function setup(){
		$this->load->spark('assets/1.5.1');
		$this->twig->display('setup_view.twig', array(
			'config'=>$this->_getConfig(),
			'assets'=>$this->assets,
    		'img_path'=>auto_link($this->config->item('index_page').'/../../install/img/get/'),
    		'install_path'=>auto_link($this->config->item('index_page').'/../../install')
		) );
	}

	public function simpleTester(){
		$this->load->add_package_path(APPPATH.'third_party/simpletester/');
		$this->load->library('simpletester');

		$this->load->helper(array('form','url'));
		$this->load->view('welcome_message', array('config'=>$this->_getConfig() ) );
	}

	public function info(){
		phpinfo();
	}

	public function saveHtaccess(){
		$this->load->helper(array('file','url'));

		$m = '';

		if(write_file('.htaccess', $this->input->post('htaccess'))){
			$m .= 'File .htaccess created successfully, trying to change config.php';

			$c = read_file(APPPATH.'config/config.php');
			$c = str_replace('$config[\'index_page\'] = \'index.php\';', '$config[\'index_page\'] = \'\';', $c);

			if (write_file(APPPATH.'config/config.php', $c)){
				$m .= '<br />Config file changed removed "index.php" in $config["index_page"] ';
			}else{
				$m .= '<br />Error changing config file, please remove "index.php" in $config["index_page"] in the config.php file manually ';
			}
		}else{
			$m .= 'Error creating .htaccess please check de file and the public folder permissions';
		}

		$this->session->set_flashdata('htaccess',$m);


		redirect('welcome/index');
	}

	public function removeHtaccess(){
		$this->load->helper(array('file','url'));

		$m = '';

		if(unlink('.htaccess')){
			$m .= 'File .htaccess removed successfully, trying to change config.php';

			$c = read_file(APPPATH.'config/config.php');
			$c = str_replace('$config[\'index_page\'] = \'\';', '$config[\'index_page\'] = \'index.php\';', $c);

			if (write_file(APPPATH.'config/config.php', $c)){
				$m .= '<br />Config file changed changed in $config["index_page"] ';
			}else{
				$m .= '<br />Error changing config file';
			}
		}else{
			$m .= 'Error removing .htaccess please check de file and the public folder permissions';
		}

		$this->session->set_flashdata('htaccess',$m);


		redirect('index.php/welcome/index');
	}

	private function _getConfig(){
		$result = array();

		$result['short_open_tag'] = ini_get('short_open_tag');
		$result['mod_rewrite'] = $this->_detectApacheModule('mod_rewrite');
		$result['mod_deflate'] = $this->_detectApacheModule('mod_deflate');
		$result['zlib_enabled'] = function_exists('gzopen');
		$result['gettext_enabled'] = function_exists('gettext');
		$result['zlib_compression_enabled'] = ini_get('zlib_output_compression');
		$result['log_write_permissions'] = $this->_get_perms(APPPATH.'logs');
		$result['cache_write_permissions'] = $this->_get_perms(APPPATH.'cache');
		$result['assets_cache_write_permissions'] = $this->_get_perms(PUBLICPATH.'assets/cache');

		return $result;
	}

	private function _get_perms($path){
		clearstatcache();
		return substr(sprintf('%o', fileperms($path)), -4);
	}

	private function _detectApacheModule($name){
		if (function_exists('apache_get_modules')) {
		  $modules = apache_get_modules();
		  return in_array($name, $modules);
		} else {
		  return getenv('HTTP_'.strtoupper($name))=='On' ? true : false ;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
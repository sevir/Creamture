<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends TWIG_Controller {
	private $assets;

	var $twig_debug = TRUE;

	function __construct()
    {
        parent::__construct();

		$this->load->library('session');

		$this->load->helper(array('form','url'));
		$this->register_function('getActualSection');

		$this->load->config('install_config');
		if ($this->config->item('show_install_only_localhost') && !$this->is_localhost())
			die(_('Access forbiden'));
    }

	public function index()
	{
		redirect('install/features');
	}

	public function features(){
		$this->display('install_view', array(
    		'img_path'=>auto_link($this->config->item('index_page').'/../../install/img/get/'),
    		'install_path'=>auto_link($this->config->item('index_page').'/../../install')
		) );
	}

	public function setup(){
		$this->load->spark('assets/1.5.1');

		$type = $this->input->get('type','overview');

		switch ($type) {
			case 'overview':
				break;
			case 'phpinfo':
				$this->twiggy->register_function('phpinfo');
				break;
			case 'server':
			case 'apache':
			case 'nginx':
				break;
			
			default:
				$type = 'overview';
				break;
		}

		$this->display($type.'_view', array(
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
		$stat = FALSE;

		if(write_file('.htaccess', $this->input->post('htaccess'))){
			$m .= _('File .htaccess created successfully, trying to change config.php');

			$c = read_file(APPPATH.'config/config.php');
			$c = str_replace('$config[\'index_page\'] = \'index.php\';', '$config[\'index_page\'] = \'\';', $c);

			if (write_file(APPPATH.'config/config.php', $c)){
				$m .= _('<br />Config file changed removed "index.php" in $config["index_page"] ');
				$stat = TRUE;
			}else{
				$m .= _('<br />Error changing config file, please remove "index.php" in $config["index_page"] in the config.php file manually');
				$stat = FALSE;
			}
		}else{
			$m .= _('Error creating .htaccess please check the file and the public folder permissions');
			$stat = FALSE;
		}

		$this->response(array(
			'stat'=>$stat,
			'msg'=> $m
		));
	}

	public function removeHtaccess(){
		$this->load->helper(array('file','url'));

		$m = '';
		$stat = false;

		if(@unlink('.htaccess')){
			$m .= _('File .htaccess removed successfully, trying to change config.php');

			$c = read_file(APPPATH.'config/config.php');
			$c = str_replace('$config[\'index_page\'] = \'\';', '$config[\'index_page\'] = \'index.php\';', $c);

			if (write_file(APPPATH.'config/config.php', $c)){
				$m .= _('<br />Config file changed changed in $config["index_page"] ');
				$stat = TRUE;
			}else{
				$m .= _('<br />Error changing config file');
				$stat = FALSE;
			}
		}else{
			$m .= _('Error removing .htaccess please check de file and the public folder permissions');
			$stat = FALSE;
		}

		$this->session->set_flashdata('htaccess',$m);

		$this->response(array(
			'stat'=>$stat,
			'msg'=> $m
		));
	}

	private function _getConfig(){
		$result = array();

		$result['short_open_tag'] = ini_get('short_open_tag');
		$result['mod_rewrite'] = $this->_detectApacheModule('mod_rewrite');
		$result['mod_deflate'] = $this->_detectApacheModule('mod_deflate');
		$result['zlib_enabled'] = function_exists('gzopen');
		$result['gettext_enabled'] = function_exists('gettext');
		$result['zlib_compression_enabled'] = ini_get('zlib_output_compression');
		$log_perms = $this->_get_perms(APPPATH.'logs');
		$result['log_write_permissions'] = ($log_perms == '0777' || $log_perms == '0775')?TRUE:FALSE;
		$cache_perms = $this->_get_perms(APPPATH.'cache');
		$result['cache_write_permissions'] = ($cache_perms == '0777' || $cache_perms == '0775')?TRUE:FALSE;
		$assetscache_perms = $this->_get_perms(PUBLICPATH.'assets/cache');
		$result['assets_cache_write_permissions'] = ($assetscache_perms == '0777' || $assetscache_perms == '0775')?TRUE:FALSE;

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

	private function is_localhost(){
		$host= gethostname();
		$ip = gethostbyname($host);
		return (strpos('127.0',$ip)>=0);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
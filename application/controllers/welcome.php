<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	public function index()
	{
		$this->load->helper(array('form','url'));

		if( strpos($_SERVER['REQUEST_URI'] , 'welcome') === FALSE){
			redirect('/welcome/');
		}

		$this->load->view('welcome_message', array('config'=>$this->_getConfig() ) );
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

	public function twigTest(){
		$this->load->add_package_path(APPPATH.'third_party/twig/');

		$this->load->library('twig', array('debug'=>true, 'template_dir'=>APPPATH.'third_party/twig/views'));

		$data = array(
			'content'=> 'Twig is a modern template engine for PHP',
			'title'=> 'Twig template system example',
			'features'=> array(
					'Django syntax',
					'Fast, compiles to plain optimized PHP code',
					'Secure, with own template language',
					'Flexible, allows to define own custom tags and filters and create its own DSL'
				)
			);
		$this->twig->display('twigTest_example.html', $data);
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
		$result['zlib_compression_enabled'] = ini_get('zlib_output_compression');
		$result['log_write_permissions'] = $this->_get_perms(APPPATH.'logs');
		$result['cache_write_permissions'] = $this->_get_perms(APPPATH.'cache');

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
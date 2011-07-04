/**
 * Creamator is the Creamture CLI Manager.
 * Type "creamator help" for commands.
 */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('CREAMATOR', APPPATH.'modules/creamator');

class Creamator extends MX_Controller {
	var $commands = array();

	function __construct(){
		parent::__construct();
		$this->load->helper(array('creamator','file'));
	}

	public function index(){
		$this->help();
	}

	public function help($command=false){
		$this->_loadCommands();

		if ($command){
			$command_info = $this->_getInfoFunc($command);

			//shows helps
			println($command);
			println('----------------------------');
			println('usage: '.@$command_info['usage']);
			if( is_array(@$command_info['parameters']) ){
				foreach($command_info['parameters'] as $p){
					println('    '.$p);
				}
			}else{
				println('    '.@$command_info['parameters']);
			}
			println('');
		}else{
			println('Commands available in this system:');
			println(' - help : Displays this help');
			println(' - help <command>: Displays the help of the <command>');
			println('');
			println('Lists of the available commands:');
			println('----------------------------');

			foreach($this->commands as $command_group=>$commands){
				println('====== '.$command_group.' ======');
				foreach($commands as $command_name=>$command_data){
					println($command_name.' : '.@$command_data['description']);
				}
			}
			println('');
		}
	}

	public function run($command=FALSE, $params=array()){
		$this->_loadCommands();

		//read the params
		//$params = func_get_args();

		if($command){
			$command_info = $this->_getInfoFunc($command);

			if ($command_info){
				if (!isset($command_info['num_parameters']) || $command_info['num_parameters'] > count($params)){
					//shows helps
					$this->help($command);
				}else{
					//Run the command
					call_user_func_array($command, $params);
					println('');
				}
			}else{
				println('Sorry the command '.$command.' doesn\'t exists');
				println('');
			}
		}else{
			println('I need the name of the command!!!');
			println('');
		}
	}

	private function _getInfoFunc($func){
		if (count($this->commands) == 0) return false;

		foreach ($this->commands as $commands){
			foreach ($commands as $command=>$data){
				if($command == $func){
					return $data;
				}
			}
		}
		return false;
	}

	private function _loadCommands(){
		//Load helpers
		if (count($this->commands) > 0) return false;

		$cremator = array();

		$this->load->helper('directory');
		$this->load->helper('file');
		$this->load->config('creamator_config');

		$tree = directory_map($this->config->item('creamator_commands'));

		foreach($tree as $file){
			if(strpos($file, '_commands.php') > 0){
				include_once($this->config->item('creamator_commands').$file);
			}
		}

		$this->commands = $creamator;
	}

	function _remap($method, $params){
		if (method_exists($this, $method) ){
			return call_user_func_array(array($this, $method), $params);
		}else{
			$this->run($method, $params);
		}
	}
}
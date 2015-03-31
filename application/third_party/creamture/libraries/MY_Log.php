<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter extension for loggin in redis
*/
class MY_Log extends CI_Log {
	var $redis;
	var $redis_enabled;
	var $log_prefix;

	function __construct(){
		try{
			$this->redis = new Redis();
			$this->redis_enabled = $this->redis->pconnect('127.0.0.1', 6379,0.01);
		}catch(Exception $e){}

		if (isset ($_SERVER['HTTP_HOST'])){
			$this->log_prefix = $_SERVER['HTTP_HOST'];
		}else{
			$this->log_prefix =  basename(realpath(__DIR__.'/../../../'));
		}
		parent::__construct();
	}

	function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	
		$level = strtoupper($level);
		
		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}

		if($this->redis_enabled){
			$this->redis->lpush($this->log_prefix.'-'.(date('Y-m-d')), $level.' - '.(date($this->_date_fmt)).' --> '.$msg);
		}else{
			parent::write_log($level, $msg, $php_error);
		}
	}
}
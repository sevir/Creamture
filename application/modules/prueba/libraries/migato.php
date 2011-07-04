<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class migato {

	var $CI; //CodeIgniter instance

	public function __construct()
	{
		$this->CI =& get_instance();
	}
}

/* eof file application/modules/prueba/libraries/migato.php */
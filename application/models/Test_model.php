<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Test_model extends CI_Base_Model {
		var $CI;
		public $_table = 'se_users';

		function __construct(){
			parent::__construct();

			$this->CI = & get_instance();
		}


	}
/* eof file application/models/test.php */
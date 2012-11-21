<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class migration_library {

	var $CI; //CodeIgniter instance

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('migrations/migration_model');

		$this->CI->load->config('migrations_config');
		if ($this->CI->config->item('automatic_migrations')){
			$this->load();
		}
	}

	public function load(){
		$this->CI->load->helper('directory');
		$dbfiles = directory_map(APPPATH.$this->CI->config->item('migrations_folder'),1);
		$migrations_loaded = array();

		sort($dbfiles, SORT_NUMERIC);

		$this->CI->load->model('migration_model');
		$lastfile = $this->CI->migration_model->getLastMigration();

		$ignore_file = ($lastfile == NULL)?FALSE:TRUE;
		foreach ($dbfiles as $file) {
			$filename = explode('.', $file);
			if ($ignore_file){				
				$ignore_file = ($filename[0] != $lastfile);
			}else{
				$this->CI->migration_model->insertMigration($filename[0]);
				if ($filename[1] == $this->CI->config->item('migrations_extension')
					|| $filename[1] == ENVIRONMENT){
					array_push($migrations_loaded, $file);
					$this->importSql(APPPATH.$this->CI->config->item('migrations_folder').DIRECTORY_SEPARATOR.$file);
				}
			}
		}

		return $migrations_loaded;
	}

	public function importSql($filename){
		$sql = $this->CI->load->file($filename, TRUE);
		$sql_lines = explode(';', $sql);

		foreach ($sql_lines as $query)
		{
			$q = str_replace(array("\n"),'',$query);
			if (! empty($q)){
				$this->CI->db->query('SET FOREIGN_KEY_CHECKS = 0');
				$this->CI->db->query($q);
				$this->CI->db->query('SET FOREIGN_KEY_CHECKS = 1');
			}			
		}
	}
}

/* eof file application/modules/migrations/libraries/migration_library.php */
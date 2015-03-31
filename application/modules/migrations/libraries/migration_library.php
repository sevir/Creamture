<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* Migration support library for Creamture
*/

class migration_library {

	var $CI; //CodeIgniter instance

	public function __construct($config=NULL)
	{
		$this->CI =& get_instance();
		$this->CI->load->model('migrations/migration_model');

		$this->CI->load->config('migrations/migrations_config');
		if( !is_null($config)){
			$this->CI->config->set_item('import_with_mysql_exec',$config['runascommandline']);
		}
		if ($this->CI->config->item('automatic_migrations')){
			$this->load(); //load migrations automatically with library instance
		}
	}

	public function load(){
		$this->CI->load->helper('directory');
		$dbfiles = directory_map(APPPATH.$this->CI->config->item('migrations_folder'),1);
		$migrations_loaded = array();
		//sort files by datetimestamp
		sort($dbfiles, SORT_NUMERIC);

		$this->CI->load->model('migration_model');
		$lastfile = $this->CI->migration_model->getLastMigration();

		$ignore_file = ($lastfile == NULL)?FALSE:TRUE;
		foreach ($dbfiles as $file) {
			$filename = explode('.', $file); //cut file name fields
			if ($ignore_file){				
				$ignore_file = ($filename[0] != $lastfile);
			}else{
				$stat = TRUE;
				if ($filename[1] == $this->CI->config->item('migrations_extension')
					|| $filename[1] == ENVIRONMENT){
					
					if( filter_var($this->CI->config->item('import_with_mysql_exec'), FILTER_VALIDATE_BOOLEAN) )
						$stat = $this->importMySQL(APPPATH.$this->CI->config->item('migrations_folder').DIRECTORY_SEPARATOR.$file);
					else
						$stat = $this->importSql(APPPATH.$this->CI->config->item('migrations_folder').DIRECTORY_SEPARATOR.$file);

					if($stat)
						array_push($migrations_loaded, $file);
				}
				if($stat){
					$this->CI->migration_model->insertMigration($filename[0]);
				}					
			}
		}

		if ($this->CI->config->item('optimize_on_migration')){
			$this->CI->load->dbutil();
			$this->CI->dbutil->optimize_database();	
		}		

		return $migrations_loaded;
	}

	public function importSql($filename){
		$sql = $this->CI->load->file($filename, TRUE);
		$sql_lines = explode(';', $sql);
		$stat = TRUE;

		$this->CI->db->query('SET FOREIGN_KEY_CHECKS = 0');
		foreach ($sql_lines as $query)
		{
			$q = str_replace(array("\n"),' ', preg_replace('/(#.*)|(--.*)/', '', $query) );
			if (preg_match('/\w+/', $q)){				
				if (! $this->CI->db->query($q)){
					echo "\n"._('ERROR Last Query: ').$this->CI->db->last_query()."\n";
					$stat = FALSE;
				}
			}
		}
		$this->CI->db->query('SET FOREIGN_KEY_CHECKS = 1');

		return $stat;
	}

	public function importMySQL($filename){
		$stat = TRUE;
		include (APPPATH.'config/database.php');
		
		if($socket = ini_get('mysql.default_socket')){
			$sock_cmd = '--protocol=socket --socket='.$socket;
		}else{
			$sock_cmd = '';
		}

		$migration_command = sprintf("mysql %s --default-character-set=".$db[$active_group]['char_set']." --user=%s --password=%s --host=%s %s < $filename", 
			$sock_cmd,
			$db[$active_group]['username'], 
			$db[$active_group]['password'], 
			$db[$active_group]['hostname'], 
			$db[$active_group]['database']);
		echo "\nTrying: ".$migration_command."\n";
		system($migration_command, $migration_status);

		if ($migration_status != 0){
			echo "\n"._('ERROR! MIGRATION FAILED! You now have a possible inconsistent database, because it did pass the initial test, but failed when the migration was actually run. You need to find out what statements of the migration did work, and bring it back to the state of the previous migration. For the record, the migration file is: ').$filename."\n";
			$stat = FALSE;
		}

		return $stat;
	}
}

/* eof file application/modules/migrations/libraries/migration_library.php */
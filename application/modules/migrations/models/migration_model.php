<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	class migration_model extends CI_Model {
		var $CI;

		function __construct(){
			parent::__construct();

			$this->load->config('migrations/migrations_config');
			$this->checkTable();
		}

		function getLastMigration(){
			$r = $this->db->select('name')->from($this->config->item('migrations_table'))->order_by('id', 'desc')->limit(1)->get()->row();
			return ($r ? $r->name:NULL);
		}

		function insertMigration($name){
			$this->db->insert($this->config->item('migrations_table'), array(
				'name'=> $name,
				'timestamp'=> date('Y-m-d H:m:s')
			));
		}

		private function checkTable(){
			$this->load->dbforge();

			$fields = array(
                'id' => array(
                                         'type' => 'INT',
                                         'constraint' => 5, 
                                         'unsigned' => TRUE,
                                         'auto_increment' => TRUE
                                  ),
                'name' => array(
                                         'type' => 'VARCHAR',
                                         'constraint' => '255'
                                  ),
                'timestamp' => array(
                                         'type' =>'TIMESTAMP'
                                  )
            );
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table($this->config->item('migrations_table'), TRUE);
		}

	}
/* eof file application/modules/migrations/models/migration.php */
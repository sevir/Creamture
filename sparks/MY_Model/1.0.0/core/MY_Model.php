<?php
/**
 * Advance CRUD actions for all models that inherit from it.
 *
 * @package CodeIgniter
 * @subpackage MY_Model
 * @link http://github.com/Se7en-IT/Base-Model-Codeigniter
 * @author Luca Musolino a.k.a Se7en <http://lucamusolino.it>
 * @copyright Copyright (c) 2012, Luca Musolino <http://lucamusolino.it>
 */

class MY_Model extends CI_Model
{
	/**
	 * The database table name
	 *
	 * @var string
	 */
	protected $_table;

	/**
	 * The primary key, by default set to `id`
	 *
	 * @var string
	 */
	protected $primary_key = 'id';

	/**
	 * An array of Codeigniter validation rules
	 *
	 * @var array
	 */
	protected $validate = array();


	public function __construct()
	{
		parent::__construct();

		if (empty($this->_table))
		{
			//tries to search for table name

			$this->load->helper('inflector');

			$class_name = preg_replace('/(_db|_model)?$/', '', get_class($this));

			$this->_table = plural(strtolower($class_name));
		}
	}

	/**
	 * The magic :)
	 *
	 */
	public function __call($name, $args)
	{
		/**
		 * find / update / delete by primary key
		 *
		 */
		if (preg_match('/^(find|update|delete)$/', $name, $m) AND count($m) == 2)
		{
			$method = $m[1];

			return call_user_func_array(array($this, $method."_by_".$this->primary_key),$args);
		}

		/**
		 * find / update / delete by custom field
		 *
		 */

		if (preg_match('/^(find|update|delete)_by_([^)]+)$/', $name, $m) AND count($m) == 3)
		{
			$method = $m[1];
			$field = $m[2];

			$values = array_shift($args);

			array_unshift($args,array($field=>$values));

			return call_user_func_array(array($this, $method."_where"),$args);
		}

		/**
		 * find / update / delete by where codeigniter condition
		 * OR
		 * find / update / delete all
		 *
		 */
		if (preg_match('/^(find|update|delete)_(where|all)$/', $name, $m) AND count($m) == 3)
		{
			$method = $m[1];
			$type =$m[2];

			if($type==="where"){
				$params = array_shift($args);
				if(is_array($params)){
					foreach($params as $field=>$values){
						if(is_array($values)){
							$this->where_in($field,$values);
						}else{
							$this->where($field,$values);
						}
					}
				}else{
					$this->where($params,NULL,FALSE);
				}
			}

			return call_user_func_array(array($this, "_".$method),$args);
		}

		/**
		 * call database driver method
		 *
		 */
		if (method_exists($this->db, $name)){
			return call_user_func_array(array($this->db,$name),$args);
		}
	}

	public function insert($data, $skip_validation = FALSE)
	{
		return $this->_insert($data, $skip_validation);
	}

	/**
	 * Save a record into the database.
	 * If the primary key exist then perform update else perform insert
	 *
	 * @param array $data
	 * @param boolean $skip_validation
	 * @return integer or boolean
	 */
	public function save($data, $skip_validation = FALSE)
	{
		$data=(object)$data;
		if(!empty($data->{$this->primary_key})){
			return $this->update($data->{$this->primary_key}, $data, $skip_validation);
		}else{
			return $this->insert($data, $skip_validation);
		}
	}

	/****************************************************************/
	/*                    PRIVATE/CORE METHOD                       */
	/****************************************************************/

	/**
	 * Get records in the database.
	 * Call before_find and after_find callbacks.
	 *
	 * @param boolean $limit
	 * @param boolean $offset
	 * @return array
	 */
	private function _find($limit=NULL,$offset=NULL)
	{
		$this->trigger_event("before_find");

		$result = $this->db->get($this->_table,$limit,$offset)
		->result();

		$this->trigger_event("after_find",array($result));

		return $result;
	}

	/**
	 * Insert a new record into the database.
	 * Call before_insert and after_insert callbacks.
	 * Return the insert ID.
	 *
	 * @param array $data
	 * @param boolean $skip_validation
	 * @return integer
	 */
	private function _insert($data, $skip_validation = FALSE)
	{
		$data =  $this->trigger_event('before_insert', array($data));
			
		$valid = ($skip_validation)?TRUE:$this->_run_validation($data);

		if ($valid)
		{
			$this->db->set($data)->insert($this->_table);
			$this->trigger_event('after_insert', array( $data, $this->db->insert_id() ));

			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Update a record in the database.
	 * Call before_update and after_update callbacks.
	 *
	 * @param array $data
	 * @param boolean $skip_validation
	 * @return bool
	 */
	private function _update($data, $skip_validation = FALSE)
	{
		$data =  $this->trigger_event('before_update', array($data));
			
		$valid = ($skip_validation)?TRUE:$this->_run_validation($data);
			
		if ($valid)
		{
			$result = $this->db->set($data)->update($this->_table);
			$this->trigger_event('after_update', array( $data, $result ));

			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Delete a record
	 * Call before_delete and after_delete callbacks.
	 *
	 * @return bool
	 */
	private function _delete()
	{
		$this->trigger_event('before_delete');
		$result = $this->db->delete($this->_table);
		$this->trigger_event('after_delete',array($result));

		return $result;
	}

	/**
	 * Runs Codeigniter validation.
	 *
	 * @return bool
	 */
	private function _run_validation($data)
	{
		if(!empty($this->validate))
		{
			foreach($data as $key => $val)
			{
				$_POST[$key] = $val;
			}

			$this->load->library('form_validation');

			if(is_array($this->validate))
			{
				$this->form_validation->set_rules($this->validate);

				return $this->form_validation->run();
			}
			else
			{
				return $this->form_validation->run($this->validate);
			}
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * Run the callbacks
	 *
	 */
	private function trigger_event($method, $params = array())
	{
		return method_exists($this, $method)?
		call_user_func_array(array($this, $method), $params):
		array_shift($params);
	}

}

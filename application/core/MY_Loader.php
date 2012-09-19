<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loader Class
 *
 * Loads views and files
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		CodeIgniter Reactor Dev Team
 * @author      Kenny Katzgrau <katzgrau@gmail.com>
 * @category	Loader
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
require_once "SPARKS_Loader.php";

class MY_Loader extends SPARKS_Loader{
    /*
    * Fix HMVC with Spark 
    */

    function __construct(){
    	parent::__construct();
    }
    
    /** Load a module view **/
	public function view($view, $vars = array(), $return = FALSE) {		
		if($path = $this->_find_spark($view, 'views/') ){
			$this->_ci_view_path = $path;
		}else{
			list($path, $view) = Modules::find($view, $this->_module, 'views/');
			$this->_ci_view_path = $path;
		}
		
		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}

    protected function _find_spark($file, $base){
    	//var_dump($this->get_package_paths());
    	$paths = $this->get_package_paths();

    	foreach ($paths as $path) {
    		if (is_file($path.$base.$file.EXT)){
    			return $path.$base;
    		}
    	}
    	return false;
    }
}
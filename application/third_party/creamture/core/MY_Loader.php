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

include(CREAMTURE_PATH.'/MX/SPARKS_Loader.php');

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

        /**
     * Load class
     *
     * This function loads the requested class.
     *
     * @param   string  the item that is being loaded
     * @param   mixed   any additional parameters
     * @param   string  an optional object name
     * @return  void
     */
    protected function _ci_load_class($class, $params = NULL, $object_name = NULL)
    {
        // Get the class name, and while we're at it trim any slashes.
        // The directory path can be included as part of the class name,
        // but we don't want a leading slash
        $class = str_replace('.php', '', trim($class, '/'));

        // Was the path included with the class name?
        // We look for a slash to determine this
        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // Extract the path
            $subdir = substr($class, 0, $last_slash + 1);

            // Get the filename from the path
            $class = substr($class, $last_slash + 1);
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class)
        {
            $subclass = APPPATH.'libraries/'.$subdir.config_item('subclass_prefix').$class.'.php';

            // Is this a class extension request?
            if ((isset($GLOBALS['libcache']) && isset($GLOBALS['libcache'][$subclass])) || file_exists($subclass))
            {
                $baseclass = BASEPATH.'libraries/'.ucfirst($class).'.php';

                if (  !( isset($GLOBALS['libcache']) && isset($GLOBALS['libcache'][$baseclass]))  && ! file_exists($baseclass))
                {
                    log_message('error', "Unable to load the requested class: ".$class);
                    show_error("Unable to load the requested class: ".$class);
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($subclass, $this->_ci_loaded_files))
                {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if ( ! is_null($object_name))
                    {
                        $CI =& get_instance();
                        if ( ! isset($CI->$object_name))
                        {
                            return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    log_message('debug', $class." class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($baseclass);
                include_once($subclass);
                $this->_ci_loaded_files[] = $subclass;

                return $this->_ci_init_class($class, config_item('subclass_prefix'), $params, $object_name);
            }

            // Lets search for the requested library file and load it.
            $is_duplicate = FALSE;
            foreach ($this->_ci_library_paths as $path)
            {
                $filepath = $path.'libraries/'.$subdir.$class.'.php';

                // Does the file exist?  No?  Bummer...
                if ( !( isset($GLOBALS['libcache']) && isset($GLOBALS['libcache'][$filepath]))  &&  ! file_exists($filepath))
                {
                    continue;
                }

                // Safety:  Was the class already loaded by a previous call?
                if (in_array($filepath, $this->_ci_loaded_files))
                {
                    // Before we deem this to be a duplicate request, let's see
                    // if a custom object name is being supplied.  If so, we'll
                    // return a new instance of the object
                    if ( ! is_null($object_name))
                    {
                        $CI =& get_instance();
                        if ( ! isset($CI->$object_name))
                        {
                            return $this->_ci_init_class($class, '', $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    log_message('debug', $class." class already loaded. Second attempt ignored.");
                    return;
                }

                include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;
                return $this->_ci_init_class($class, '', $params, $object_name);
            }

        } // END FOREACH

        // One last attempt.  Maybe the library is in a subdirectory, but it wasn't specified?
        if ($subdir == '')
        {
            $path = strtolower($class).'/'.$class;
            return $this->_ci_load_class($path, $params);
        }

        // If we got this far we were unable to find the requested class.
        // We do not issue errors if the load call failed due to a duplicate request
        if ($is_duplicate == FALSE)
        {
            log_message('error', "Unable to load the requested class: ".$class);
            show_error("Unable to load the requested class: ".$class);
        }
    }

}
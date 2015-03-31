<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * Paris and Idiorm ORM autoloader for CodeIgniter
 */
class CI_Orm
{
    public function __construct(){
        include APPPATH.'config/database.php';

        //Alternative named configuration
        foreach ($db as $db_conf => $altdb) {
	ORM::configure(
		$altdb['dbdriver'].':host='.$altdb['hostname'].
		';dbname='.$altdb['database'].
		';charset='.$altdb['char_set'], null, $db_conf);
	ORM::configure('username', $altdb['username'], $db_conf);
	ORM::configure('password', $altdb['password'], $db_conf);
        }

        //Default configuration
        ORM::configure(
        	$db[$active_group]['dbdriver'].':host='.$db[$active_group]['hostname'].
        	';dbname='.$db[$active_group]['database'].
        	';charset='.$db[$active_group]['char_set']);
        ORM::configure('username', $db[$active_group]['username']);
        ORM::configure('password', $db[$active_group]['password']);

        spl_autoload_register(function($class){
        	if (file_exists(APPPATH.'models/'.$class.'.php'))
        		include(APPPATH.'models/'.$class.'.php');
        });
    }
}
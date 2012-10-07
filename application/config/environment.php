<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

/*
 * OS Detection
 */
	$os = array(''=>false);
	$os['windows'] = preg_match('/^win/i', PHP_OS);
	$os['unix'] = preg_match('/^linux|unix/i', PHP_OS);
	$os['osx'] = preg_match('/mac/i', PHP_OS);


/*
 * 	Global configuration
 */
	$automatic_environment = TRUE;
	$manual_environment = 'development';

/*
 *	Define here the production and devel server name list
 *  get the server name with command php_uname('n')
 */ 

	$prod_server_name = array();
	$testing_server_name = array();
	$devel_server_name = array();

/*
 *	Define environment by OS
 */
	$prod_os = '';  // write: unix or windows or osx
	$testing_os = ''; // write: unix or windows or osx
	$devel_os = ''; // write: unix or windows or osx

/*
 *	Set environment configuration
 */

	if ($automatic_environment){
		//detect by server name
		if (in_array(php_uname('n'), $prod_server_name) || $os[$prod_os])
			$env = 'production';
		else if (in_array(php_uname('n'), $testing_server_name) || $os[$testing_os])
			$env = 'testing';
		else if (in_array(php_uname('n'), $devel_server_name) || $os[$devel_os])
			$env = 'development';
		else
			$env = 'development';
	}else{
		$env = $manual_environment;
	}



	define('ENVIRONMENT', $env);
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

/*
 * Load PHP_Error Library
 */
require(APPPATH.'third_party/php_error/php_error.php');
$e = \php_error\reportErrors(array(
  'application_folders' => APPPATH,
  'ignore_folders' => BASEPATH
));

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
			$e->turnOn();
		break;

		case 'testing':
		case 'production':
			error_reporting(0);
			$e->turnOff();
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

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
 * 	Global configuration
 */
	$config['automatic_environment'] = TRUE;

/*
 * Enable or disable better error reporting using php_error library
 */
	$config['enable_error_improvement'] = TRUE; 

/*
 * Default configuration in manual mode
 */
	$config['manual_environment'] = 'development';

/*
 *	Define here the production and devel server name list
 *  get the server name with command php_uname('n')
 */ 

	$config['prod_server_name'] = array();
	$config['testing_server_name'] = array();
	$config['devel_server_name'] = array();

/*
 *	Define environment by OS
 */
	$config['prod_os'] = '';  // write: unix or windows or osx
	$config['testing_os'] = ''; // write: unix or windows or osx
	$config['devel_os'] = ''; // write: unix or windows or osx



<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Extensions for Twig
 *
 * @package		Creamture
 * @author		Digio
 */

/**
 * HMVC
 */

if ( ! function_exists('modules_run'))
{
	/**
	 * Let Twig load HMVC modules
	 */
	function modules_run($path)
	{
		echo modules::run($path);
	}
}

/* EOF */
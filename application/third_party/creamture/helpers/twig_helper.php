<?php

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

if ( ! function_exists('getActualSection'))
{
	function getActualSection($path, $string = 'active', $regexp = TRUE)
	{
		$pattern = str_replace('/','',$path);
		$subject = str_replace('/','',$_SERVER['REQUEST_URI']);
		if($regexp) {
			if (preg_match('/'.$pattern.'/', preg_quote($subject))) {
				return $string;
			} else {
				return '';
			}
		} else {
			if (strpos($pattern, $subject) !== FALSE) {
				return $string;
			} else {
				return '';
			}
		}
	}
}

/* End of file twiggy_helper.php */
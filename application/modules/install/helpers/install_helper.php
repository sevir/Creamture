<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

function getActualSection($path, $string = 'active', $regexp = TRUE)
{
	$pattern = str_replace('/','',$path);
	$subject = str_replace('/','',uri_string());
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
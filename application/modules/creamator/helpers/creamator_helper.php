<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Creamator is the CRUD command line client for Creamture
 * You can extend with new command functions.
 * SeVIR @2011
 */


function println($line){
	print($line."\r\n");
}

function creamator_tpl($rpl = array(), $tpl_str=''){
	foreach($rpl as $k=>$v){
		$tpl_str = str_replace($k,$v,$tpl_str);
	}
	return $tpl_str;
}

function is_win(){
	return (strtoupper (substr(PHP_OS, 0,3)) == 'WIN');
}

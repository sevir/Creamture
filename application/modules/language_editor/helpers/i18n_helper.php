<?php

/**
 * Merge two arrays recursively
 */
function array_extend($a, $b) {
	foreach($b as $k => $v) {
		if(is_array($v)) {
			if(!isset($a[$k])) {
				$a[$k] = $v;
			} else {
				$a[$k] = array_extend($a[$k], $v);
			}
		} else {
			$a[$k] = $v;
		}
	}
	return $a;
}

function getRelativePath($path=''){
	$ci = & get_instance();
	if($ci->config->item('index_page') != ''){
		preg_match(
			'/'.str_replace('.','\.',$ci->config->item('index_page')).'\/?(.*)/',
			$_SERVER['PHP_SELF'],
			$coincidencias
		);

		$n = count(explode('/',$coincidencias[1]));
		$r = '/';
		for($i=0;$i<$n;$i++){
			$r .='../';
		}
		$r = $ci->config->item('index_page').$r.$path;
	}else{
		if($ci->config->item('base_url')){
			$r = $ci->config->item('base_url').$path;
		}else{
			$r = $path;
		}
	}

	return str_replace('http:/','http://',preg_replace('/\/+/','/',$r));
}

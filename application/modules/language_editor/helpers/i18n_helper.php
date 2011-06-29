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
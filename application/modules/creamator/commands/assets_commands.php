<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['Assets Commands'] = array(
	'clearAssetsCache' => array(
		'description'=>'Remove cache files of assets library',
		'usage' => 'creamator clearAssetsCache <type>',
		'parameters' => array('optional type [css, js, img] if ommited clear all'),
		'num_parameters' => 0
	)
);

function clearAssetsCache($type=null){
    $CI = & get_instance();

    $CI->load->helper('directory');

    $map = directory_map('./public/assets/cache/');

    foreach ($map as $value) {
        if ($type && strpos($value, '.'.$type)){
            unlink('./public/assets/cache/'.$value);
        }else{
            if ($value != 'index.html' && $type==null)
                unlink('./public/assets/cache/'.$value);
        }
    }
    
    println('Cache cleared');
}


<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['Deploy Commands'] = array(
	'checkInstallation' => array(
		'description'=>'Execute script for deploy, you can check folder permissions, etc..',
		'usage' => 'creamator deploy',
		'num_parameters' => 0
	)
);

function checkInstallation(){
    $CI = & get_instance();

	if(! _checkInstallation_check_writable_folder(APPPATH,'cache')){
    	println('ERROR: Please create and check for writting permissions in the folder "cache" in your "application" folder!');
    }
	if(! _checkInstallation_check_writable_folder(PUBLICPATH.'assets'.DIRECTORY_SEPARATOR,'cache')){
    	println('ERROR: Please create and check for writting permissions in the folder "cache" in your "public'.DIRECTORY_SEPARATOR.'assets" folder!');
    }
    if(! _checkInstallation_check_writable_folder(PUBLICPATH,'upload')){
    	println('ERROR: Please create and check for writting permissions in the folder "upload" in your "public" folder!');
    }
    
    println('Check installation permissions finished');
}

function _checkInstallation_check_writable_folder($path, $folder){
	if( ! file_exists($path.$folder) ){
		if (is_writable($path)){
			mkdir($path.$folder);
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return is_writable($path.$folder);
	}
}
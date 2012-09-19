<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Master switch
|--------------------------------------------------------------------------
|


/*
|--------------------------------------------------------------------------
| Site Language
|--------------------------------------------------------------------------
|
| Available language. This is the list of language for which there is
| a corresponding file :
|
|
*/
$config['available_languages']	= array('en');

$config['i18n_users'] = array(
	'english_translator'=> array(
		'password'=>'patricio',
		'admin'=>FALSE,
		'langs'=>array('en')  //Only english translator
	),
	'editor'=> array(
		'password'=>'3d1t0rp4ssw0rd',
		'admin'=>TRUE,  /* ADMIN = ALL PERMS FOR i18n_manager */
		'langs'=> $config['available_languages']  /* ALL LANGS */
	)
);

/*
|--------------------------------------------------------------------------
| Path
|--------------------------------------------------------------------------
|
| Path that contains the system and custom language file. Typically , this
| will be ./system/language/ and ./system/application/language/
|
|
*/
$config['path']			= BASEPATH.'language/';
$config['path_custom']	= APPPATH.'language/';

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Controller that handles the editor action
| Default is 'editor'
|
|
*/
$config['base_controller']		= 'language_editor/';


/* End of file language_editor.php */
/* Location: ./system/application/config/language_editor.php */
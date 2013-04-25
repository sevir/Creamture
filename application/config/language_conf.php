<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['domain'] = 'creamture';
$config['default_locale'] = 'en_US';
$config['available_locales'] = array('en_US','es_ES');
$config['locale_folders'] = array('en','es');
$config['language_aliases'] = array(
	'es_ES'=>array('es','es_','es-')
);
$config['locale_dir'] = APPPATH.'../i18n/locales/';
$config['encoding'] = 'UTF-8';
$config['twig_tmp_locale_dir'] = APPPATH.'../i18n/twig_tmp/';

/* eof */
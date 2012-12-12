<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['domain'] = 'creamture';
$config['default_locale'] = 'es_GB';
$config['available_locales'] = array('en_GB');
$config['language_aliases'] = array(
	'en_GB'=>array('en','en_')
);
$config['locale_dir'] = APPPATH.'../i18n/locales/';
$config['encoding'] = 'UTF-8';
$config['twig_tmp_locale_dir'] = APPPATH.'../i18n/twig_tmp/';

/* eof */
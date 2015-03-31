<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['domain'] = 'creamture';
$config['default_locale'] = 'en_US';
$config['available_locales'] = array('en_US');
$config['locale_folders'] = array('en_US');
$config['language_aliases'] = array(
	'en_US'=>array('en','en_','en-')
);
$config['locale_dir'] = APPPATH.'i18n/locales/';
$config['encoding'] = 'UTF-8';
$config['twig_tmp_locale_dir'] = APPPATH.'i18n/twig_tmp/';

/* eof */
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['template_dir'] = array(APPPATH.'views', APPPATH.'modules');

$config['cache_dir'] = APPPATH.'cache';

$config['debug'] = ENVIRONMENT === 'production' ? FALSE : TRUE;

/* EOF */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Automatic migrations on load library
*/
$config['automatic_migrations'] = FALSE;
$config['migrations_table'] = 'migrations_history';
$config['migrations_folder'] = 'db';
$config['migrations_extension'] = 'sql';
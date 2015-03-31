<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Automatic migrations on load library
$config['automatic_migrations'] = FALSE;

// name of the migration table
$config['migrations_table'] = 'migrations_history';

// name of the folder relative to the Application path
$config['migrations_folder'] = 'db';

// file extensions of the migrations
$config['migrations_extension'] = 'sql';

// execute an optimize database at the end of the migration process
$config['optimize_on_migration'] = TRUE;

// use mysql executable for sql importations (it is more fast in big sql files)
// mysql command must be in the system path
$config['import_with_mysql_exec'] = FALSE;
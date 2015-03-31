<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['Migrations Commands'] = array(
    'createMigration' => array(
        'description'=>_('Create an empty migration file'),
        'usage' => _('creamator createMigration <desc> <environment>'),
        'parameters'=> array(_('file description'),_('optional, the environment')),
        'num_parameters' => 1
    ),
    'loadMigrations' => array(
        'description'=>_('load all available migrations'),
        'usage' => _('creamator loadMigrations'),
        'num_parameters' => 0
    )
);

function createMigration($desc, $env = NULL ){
    $CI = & get_instance();
    $CI->load->config('migrations/migrations_config');

    $CI->load->helper('file');
    $filedesc = str_replace(array(' ','.',',','?'), '', $desc);
    $filename = date('YmdHis').'_'.$filedesc.'.'.(($env)?$env.'.':'').$CI->config->item('migrations_extension');
    if(!write_file(
        APPPATH.$CI->config->item('migrations_folder').
        DIRECTORY_SEPARATOR.$filename,
        ''
        )){
        println(_('Error creating migration file, please check folder permissions'));
    }else{
        println(_('Migration file ').$filename.' created.');
    }
}

function loadMigrations(){
    $CI = & get_instance();

    $CI->load->config('migrations/migrations_config');
    $CI->load->library('migrations/migration_library');

    $m = array();
    if (!$CI->config->item('automatic_migrations')){
        $m = $CI->migration_library->load();
    }
    
    foreach ($m as $fileloaded) {
        println(_('Migration file loaded: ').$fileloaded);
    }
    if (empty($m))
        println(_('No available migrations...'));
    println(_('Migrations finished'));
}


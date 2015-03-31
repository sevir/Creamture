<?php if (!defined('CREAMATOR')) {
	exit('Functions only for Creamator Manager');
}

/* Functions definition */
$creamator['Crontab Commands'] = array(
	'enableCrontab' => array(
		'description' => _('Create an empty migration file'),
		'usage' => _('creamator createMigration <desc> <environment>'),
		'num_parameters' => 0,
	),
	'disableCrontab' => array(
		'description' => _('Create an empty migration file'),
		'usage' => _('creamator createMigration <desc> <environment>'),
		'num_parameters' => 0,
	),
);

function enableCrontab() {
	include_once 'vendor/mediovskitechnology/php-crontab-manager/src/CrontabManager.php';

	$crontab = new php\manager\crontab\CrontabManager();
	$crontab->enableOrUpdate('.crontab');
	$crontab->save();
}

function disableCrontab(){
            include_once 'vendor/mediovskitechnology/php-crontab-manager/src/CrontabManager.php';

            $crontab = new php\manager\crontab\CrontabManager();
            $crontab->disable('.crontab');
            $crontab->save();
}
<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['basicCommands'] = array(
	'createController' => array(
		'description'=>'Create new controller',
		'usage' => 'creamator createController <name_of_the_controller>',
		'parameters' => 'name_of_controller must be a string without spaces',
		'num_parameters' => 1
	),
	'createModel' => array(
		'description'=>'Create new model',
		'usage' => 'creamator createModel <name_of_the_model>',
		'parameters' => 'name_of_the_model must be a string without spaces',
		'num_parameters' => 1
	),
	'createLibray' => array(
		'description'=>'Create new Library',
		'usage' => 'creamator createLibrary <name_of_the_library>',
		'parameters' => 'name_of_the_library must be a string without spaces',
		'num_parameters' => 1
	),
	'createHelper' => array(
		'description'=>'Create new Helper',
		'usage' => 'creamator createHelper <name_of_the_helper>',
		'parameters' => 'name_of_the_helper must be a string without spaces',
		'num_parameters' => 1
	),
	'createModule' => array(
		'description'=>'Create new Module Scaffolding',
		'usage' => 'creamator createModule',
		'parameters' => 'name_of_the_module must be a string without spaces',
		'num_parameters' => 1
	),
	'createModuleController' => array(
		'description'=>'Create new controller in a module',
		'usage' => 'creamator createModuleController <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module must be a string without spaces','name_of_the_controller must be a string without spaces'),
		'num_parameters' => 2
	),
	'createModuleLibrary' => array(
		'description'=>'Create new library in a module',
		'usage' => 'creamator createModuleLibrary <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module must be a string without spaces','name_of_the_controller must be a string without spaces'),
		'num_parameters' => 2
	),
	'createModuleHelper' => array(
		'description'=>'Create new helper in a module',
		'usage' => 'creamator createModuleHelper <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module must be a string without spaces','name_of_the_controller must be a string without spaces'),
		'num_parameters' => 2
	),
	'enableHost' => array(
		'description'=>'Enable new host in hosts file DNS resolution',
		'usage' => 'creamator enableHost <name_of_the_host> <ip_of_the_host>',
		'parameters' => array('name_of_the_host is mandatory, like my.host.com','ip_of_the_host is optional, by default is localhost 127.0.0.1'),
		'num_parameters' => 1
	),
	'disableHost' => array(
		'description'=>'Remove host in hosts file DNS resolution',
		'usage' => 'creamator disableHost <name_of_the_host>',
		'parameters' => array('name_of_the_host is mandatory, like my.host.com'),
		'num_parameters' => 1
	)
);

function createController($name, $module=FALSE){
	println('Creating the controller '.$name);
	$tpl_path = CREAMATOR.'/templates/controller_template.cpl';

	if(file_exists($tpl_path)){
		if($module){
			$file_path = APPPATH.'modules/'.$module.'/controllers/'.strtolower($name).'.php';
		}else{
			$file_path = APPPATH.'controllers/'.strtolower($name).'.php';
		}

		$tpl = read_file($tpl_path);

		if(! write_file(
			$file_path,
			creamator_tpl(
				array(
					'%NAME%'=> ucwords($name),
					'%FILE_PATH%'=> $file_path
				),
			$tpl)
		)){
			println('Error creating the controller file, please check the controller folder permissions');
		}

		println('Controller '.$name.' created successfully!');
	}else{
		println('No template controller_template.cpl has been found in the template folder');
	}
}

function createModel($name, $module=FALSE){
	println('Creating the model '.$name);
	$tpl_path = CREAMATOR.'/templates/model_template.cpl';

	if(file_exists($tpl_path)){
		if($module){
			$file_path = APPPATH.'modules/'.$module.'/models/'.strtolower($name).'.php';
		}else{
			$file_path = APPPATH.'models/'.strtolower($name).'.php';
		}

		$tpl = read_file($tpl_path);

		if(! write_file(
			$file_path,
			creamator_tpl(
				array(
					'%NAME%'=> strtolower($name),
					'%FILE_PATH%'=> $file_path
				),
			$tpl)
		)){
			println('Error creating the model file, please check the models folder permissions');
		}

		println('Model '.$name.' created successfully!');
	}else{
		println('No template model_template.cpl has been found in the template folder');
	}
}

function createLibrary($name, $module=FALSE){
	println('Creating the library '.$name);
	$tpl_path = CREAMATOR.'/templates/library_template.cpl';

	if(file_exists($tpl_path)){
		if($module){
			$file_path = APPPATH.'modules/'.$module.'/libraries/'.strtolower($name).'.php';
		}else{
			$file_path = APPPATH.'libraries/'.strtolower($name).'.php';
		}

		$tpl = read_file($tpl_path);

		if(! write_file(
			$file_path,
			creamator_tpl(
				array(
					'%NAME%'=> strtolower($name),
					'%FILE_PATH%'=> $file_path
				),
			$tpl)
		)){
			println('Error creating the library file, please check the libraries folder permissions');
		}

		println('Library '.$name.' created successfully!');
	}else{
		println('No template library_template.cpl has been found in the template folder');
	}
}

function createHelper($name, $module=FALSE){
	println('Creating the helper '.$name);
	$tpl_path = CREAMATOR.'/templates/helper_template.cpl';

	if(file_exists($tpl_path)){
		if($module){
			$file_path = APPPATH.'modules/'.$module.'/helpers/'.strtolower($name).'_helper.php';
		}else{
			$file_path = APPPATH.'helpers/'.strtolower($name).'_helper.php';
		}

		$tpl = read_file($tpl_path);

		if(! write_file(
			$file_path,
			creamator_tpl(
				array(
					'%FILE_PATH%'=> $file_path
				),
			$tpl)
		)){
			println('Error creating the helper file, please check the helpers folder permissions');
		}

		println('Helper '.$name.' created successfully!');
	}else{
		println('No template helper_template.cpl has been found in the template folder');
	}
}

function createModule($name){
	println('Creating the module '.$name);

	if (mkdir(APPPATH.'modules/'.strtolower($name), 0755, TRUE ) ){
		mkdir(APPPATH.'modules/'.strtolower($name).'/config');
		mkdir(APPPATH.'modules/'.strtolower($name).'/controllers');
		mkdir(APPPATH.'modules/'.strtolower($name).'/libraries');
		mkdir(APPPATH.'modules/'.strtolower($name).'/views');
		mkdir(APPPATH.'modules/'.strtolower($name).'/helpers');

		println('Module folders '.$name.' created successfully!');
	}else{
		println('Error creating the module folders, please check the permissions of modules folder');
	}
}

function createModuleController($module, $name){
	createController($name, $module);
}

function createModuleLibrary($module, $name){
	createLibrary($name, $module);
}

function createModuleHelper($module, $name){
	createHelper($name, $module);
}

function enableHost($name, $ip='127.0.0.1'){
	if(!preg_match('/(\w+)\.?(\w+)/',$name) || !preg_match('/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/',$ip)){
		println('Please check the name of ip syntax');
		return false;
	}

	if(is_win()){
		$win_path = (is_dir('/windows'))?'/windows':'/winnt';

		$host_path = $win_path.'/system32/drivers/etc/hosts';
	}else{
		$host_path = '/etc/hosts';
	}

	$host_file = read_file($host_path);

	if (strpos($host_file, $name)){
		$host_file = preg_replace('/'.'\s*'.'([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])'.'\s+'.str_replace('.','\.',$name).'\s*$/', "\n".$ip.' '.$name."\n", $host_file );

	}else{
		$host_file .= "\n".$ip." ".$name."\n";
	}

	write_file($host_path,$host_file);

	println('Host '.$name.' enabled in hosts file successfully!');
}

function disableHost($name){
	if(!preg_match('/(\w+)\.?(\w+)/',$name) ){
		println('Please check the name syntax');
		return false;
	}

	if(is_win()){
		$win_path = (is_dir('/windows'))?'/windows':'/winnt';

		$host_path = $win_path.'/system32/drivers/etc/hosts';
	}else{
		$host_path = '/etc/hosts';
	}

	$host_file = read_file($host_path);

	$host_file = preg_replace('/\s+[0-9,\.]+\s+'.str_replace('.', '\.', $name).'/', '', $host_file);

	write_file($host_path, $host_file);

	println('Host '.$name.' removed in hosts file successfully!');
}
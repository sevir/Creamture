<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['basicCommands'] = array(
	'createController' => array(
		'description'=>'Create new controller',
		'usage' => 'creamator createController <name_of_the_controller>',
		'parameters' => 'name_of_controller',
		'num_parameters' => 1
	),
	'createModel' => array(
		'description'=>'Create new model',
		'usage' => 'creamator createModel <name_of_the_model>',
		'parameters' => 'name_of_the_model',
		'num_parameters' => 1
	),
	'createLibray' => array(
		'description'=>'Create new Library',
		'usage' => 'creamator createLibrary <name_of_the_library>',
		'parameters' => 'name_of_the_library',
		'num_parameters' => 1
	),
	'createHelper' => array(
		'description'=>'Create new Helper',
		'usage' => 'creamator createHelper <name_of_the_helper>',
		'parameters' => 'name_of_the_helper',
		'num_parameters' => 1
	),
	'createModule' => array(
		'description'=>'Create new Module Scaffolding',
		'usage' => 'creamator createModule',
		'parameters' => 'name_of_the_module',
		'num_parameters' => 1
	),
	'createModuleController' => array(
		'description'=>'Create new controller in a module',
		'usage' => 'creamator createModuleController <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module','name_of_the_controller'),
		'num_parameters' => 2
	),
	'createModuleLibrary' => array(
		'description'=>'Create new library in a module',
		'usage' => 'creamator createModuleLibrary <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module','name_of_the_controller'),
		'num_parameters' => 2
	),
	'createModuleHelper' => array(
		'description'=>'Create new helper in a module',
		'usage' => 'creamator createModuleHelper <name_of_the_module> <name_of_the_controller>',
		'parameters' => array('name_of_the_module','name_of_the_controller'),
		'num_parameters' => 2
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




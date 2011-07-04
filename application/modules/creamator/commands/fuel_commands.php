<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['FuelCMS Helper Commands'] = array(
	'getFuelMyModel' => array(
		'description'=>'Download an updated version of FuelCMS MY_Model patch',
		'usage' => 'creamator getFuelMyModel',
		'num_parameters' => 0
	)
);

function getFuelMyModel(){
	if( file_exists(APPPATH.'core/MY_Model.php')){
		println('You have a previous MY_Model.php.');
		println('Renaming your old MY_Model.php to MY_Model.old.php');
		rename(APPPATH.'core/MY_Model.php',APPPATH.'core/MY_Model.old.php');

		getFuelMyModel();
	}else{
		$m = file_get_contents('https://raw.github.com/daylightstudio/FUEL-CMS/demo/fuel/application/core/MY_Model.php');
		if (count($m)>0){
			if( write_file(APPPATH.'core/MY_Model.php', $m ) ){
				println('MY_Model.php downloaded successfully!');
			}else{
				println('Error writing MY_Model.php, please checks the folder permissions');
			}
		}else{
			println('Error downloading file : https://raw.github.com/daylightstudio/FUEL-CMS/demo/fuel/application/core/MY_Model.php');
		}
	}
}





<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Img extends CI_Controller {
	public function get($filename)
	{
		if (strpos($filename, '..')>0 || strpos($filename,'/')>0 || strpos($filename, '.php')>0)
			exit(0);

		$ext = substr($filename, strrpos($filename, '.')+1);

		if ($ext == 'jpg'){
			 header('Content-Type: image/jpeg');
		}else{
			 header('Content-Type: image/'.$ext);
		}
		
		if(file_exists(dirname(__FILE__).'/../assets/img/'.$filename))
			readfile(dirname(__FILE__).'/../assets/img/'.$filename);
	}
}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Language Editor Class
 *
 * Allows editing of the language files in Code Igniter.
 *
 * @author		jcavard & sevir
 * @version		0.0.2
 * @link		[yet-to-come]
 * @license		[yet-to-come]
 */

class I18n_manager
{
	// Private

	// language available for your site
	var $_available_languages	= array();

	// path to system language files
	var $_path					= '';

	// path to custom language files
	var $_path_custom			= '';

	// base controller
	var $_base_controller		= '';
	// menu
	var $_menu_controller		= '';
	// edit form
	var $_form_controller		= '';
	// save
	var $_process_controller	= '';

	var $_files					= array();


	function I18n_manager()
	{
		$this->ci =& get_instance();

		log_message('debug', 'Language Editor initialized');

		// Load required library
		$this->ci->load->helper('directory');
		$this->ci->load->helper('file');

		$this->ci->config->load('language_editor');
		// Initialize
		$this->_init();
	}

	function find_file($language, $line)
	{

		foreach($this->_files[$language] as $key => $val)
		{

			$tmp = array();
			$tmp	= $this->get_language_file_content($language, $key);

			// returns the file if line id is found
			if(array_key_exists($line, $tmp))
			{
				return array($key, $tmp);
			}
		}
		return false;
	}


	// Returns array
	function get_language_file_content($language, $file)
	{
		if(!$this->_language_exists($language))
			return;

		// get file content as string
		$str	= read_file($this->_files[$language][$file]);

		// strip of PHP tag
		$str	= str_replace('<?php', '', $str);

		// eval to get the $lang array
		eval($str);
		ksort($lang);
		return $lang;
	}

	function get_list_languages()
	{
		$html	= '';
		foreach($this->_available_languages as $language)
		{
			$html	.= '<a href="' . site_url($this->_menu_controller . $language) . '">' . $language . '</a><br />';
		}
		return $html;
	}

	function get_list_files($language)
	{
		if(!$this->_language_exists($language))
			return;

		$html	= '';
		foreach($this->_files[$language] as $key => $val)
		{
			if(!preg_match('/_lang.php$/i', $key))
				continue;

			$short_name	= str_replace('_lang.php', '', $key);
			if ($short_name!=''){
				if ($this->ci->session->userdata('i18n_admin')){
					$html	.= '<a href="' . site_url($this->_form_controller . $language . '?file=' . $short_name) .
						'">' . $short_name . '</a> <a href="'.site_url('language_editor/remove/'.$language.'/?file=' . $short_name) .
						'" class="remove" onclick="this.result=window.prompt(\'Are U Sure? write Y\').toUpperCase(); this.href+=\'&confirm=\'+this.result;  return ( this.result ==\'Y\')">[remove]</a><br />';
				}else{
					$html	.= '<a href="' . site_url($this->_form_controller . $language . '?file=' . $short_name) .
						'">' . $short_name . '</a><br />';
				}
			}

		}
		return $html;
	}

	function remove_files($filename){
		if(!preg_match('/_lang.php$/i', $filename))
				$filename	= $filename . '_lang.php';

		$result = TRUE;
		foreach($this->_available_languages as $lang){
			if( !unlink($this->_path_custom.$lang.'/'.$filename) ){
				$result = FALSE;
			}
		}

		return $result;
	}

	function put_recursive($key, $val, & $old_array){
		if (is_array($val) || is_object($val)){
			foreach ($val as $k => $v){
				$this->put_recursive($key.'/'.$k, $v,  $old_array);
			}
		}else{
			$old_array = array_merge($old_array, array($key=> $val));
		}
	}

	function put_content($language, $file, $post)
	{

		if(!$this->_language_exists($language))
			return;

		/* Guardamos o creamos todos los strings nuevos en todos los idiomas */
		foreach($this->_files as $keylang => $filelang){
			if(!preg_match('/_lang.php$/i', $file))
				$file	= $file . '_lang.php';

			$post_e = array();
			$remove_keys = array();
			foreach($post as $key => $value)
			{
				$a = array();
				$this->put_recursive($key, $value, $a);
				$post_e = array_merge($post_e, $a);
				if (($this->ci->session->userdata('i18n_admin')) && $value=='XXXREMOVEXXX')
				{
					array_push($remove_keys, $key);
				}
			}

			if(isset($filelang[$file]) && file_exists($filelang[$file])){
				$str	= read_file($filelang[$file]);

				// strip of PHP tag
				$str	= str_replace('<?php', '', $str);
				$lang = array();

				// eval to get the $lang array
				eval($str);

				$lang_e = array();
				foreach($lang as $key => $value)
				{
					$a = array();
					$this->put_recursive($key, $value, $a);
					$lang_e = array_merge($lang_e, $a);
				}

				if($language == $keylang){
					$post_r = array_merge($lang_e,$post_e);
				}else{
					$post_r = array_merge($post_e,$lang_e);
				}


			}else{
				if( !file_exists(APPPATH.'language/'.$keylang.'/'.(substr($file,0,strrpos($file,'/'))))){
					mkdir(APPPATH.'language/'.$keylang.'/'.(substr($file,0,strrpos($file,'/'))),0775,TRUE);
				}

				$filelang[$file] = APPPATH.'language/'.$keylang.'/'.$file;
				$post_r = $post_e;
			}

			$output	= '<?php

	';

			$string		= '';

			foreach ($post_r as $kv => $vk){
				if( (!$this->ci->session->userdata('i18n_admin')) ||
					( ($this->ci->session->userdata('i18n_admin'))  && !in_array($kv, $remove_keys) )){
					$string .= '$lang';
					$ex = explode('/', $kv);
					foreach($ex as $vv){
						$string .= '['.( (is_numeric($vv))?$vv:'\''.$vv.'\'' ).']';
					}
					if($vk != 'TRUE' && $vk != 'FALSE')
					{
						$string .= '= \'' . str_replace('\"','"',addslashes($vk)) . '\';'."\n";
					}
					else
					{
						$string .= '= ' . str_replace('\"','"',addslashes($vk)) . ';'."\n";
					}
				}
			}

			$output	.= $string;
			$output	.= '

	/* End of file ' . $file . ' */
	/* Location: '.APPPATH.'language/' . $keylang . '/' . $file . ' */';

			if(!write_file($filelang[$file], $output)){
				log_message('ERROR', 'Unable to save file: ' . $file);
				return false;
			}

		}
		return true;
	}





	/* Private function */

	function _return_paths($path, $l, $files){
		$return = array();
		foreach($files as $newpath=>$file){
			if(is_array($file)){
				$return = array_merge($return, $this->_return_paths($path.'/'.$newpath, $l, $file));
			}else{
				$return[$path.'/'.$file] = $this->_path_custom . $l . '/' . $path.'/'.$file;
			}
		}

		return $return;
	}

	function _init()
	{
		$this->_available_languages	= $this->ci->config->item('available_languages');
		$this->_path				= $this->ci->config->item('path');
		$this->_path_custom			= $this->ci->config->item('path_custom');
		$this->_base_controller		= $this->ci->config->item('base_controller');

		$this->_menu_controller		= $this->_base_controller . 'menu/';
		$this->_form_controller		= $this->_base_controller . 'edit/';
		$this->_process_controller	= $this->_base_controller . 'save/';


		$tmp		= directory_map($this->_path);
		$tmp_custom	= directory_map($this->_path_custom);


		foreach($this->_available_languages as $l)
		{
			$this->_files[$l]	= array();

			foreach($tmp_custom[$l] as $path=>$file)
			{
				if(is_array($file)){
					$this->_files[$l] = array_merge($this->_files[$l] , $this->_return_paths($path, $l, $file));
				}else{
					if(!preg_match('/_lang.php$/i', $file))
						continue;
					$this->_files[$l][$file] = $this->_path_custom . $l . '/' .$file;
				}
			}

			ksort($this->_files[$l]);
		}
	}

	function _language_exists($language)
	{
		if(!in_array($language, $this->_available_languages))
		{
			log_message('ERROR', sprintf('Language %s is not available', $language));
			return false;
		}
		return true;
	}

	/*  End Private function */
}

/* End of file datamapper.php */
/* Location: ./application/libraries/language_editor.php */
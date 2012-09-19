<?php
/**
 * Editor de idiomas
 * @package symposium
 */
/**
 * Editor de idiomas
 * @package symposium
 */
class Language_editor extends CI_Controller
{

	var $scripts = array();
	var $styles = array();

	function __construct()
	{
		parent::__construct();

		$this->load->library(array('session','i18n_manager'));
		$this->load->helper(array('url','i18n'));

		$this->scripts = array(
			getRelativePath('/language_editor/assets/js/jquery-1.6.1.min.js'),
			getRelativePath('/language_editor/assets/ckeditor/ckeditor.js'),
			getRelativePath('/language_editor/assets/ckeditor/adapters/jquery.js'),
			'http://www.google.com/jsapi',
			getRelativePath('/language_editor/assets/js/editor.js'),
		);
		$this->styles = array(
			getRelativePath('/language_editor/assets/css/language_editor.css'),
		);
	}

	function assets(){
		$this->load->helper(array('url','file'));
		$path = uri_string();
		$path = APPPATH.'modules/'.$path;

		if (file_exists($path)){
			$this->output->set_header('Content-type: '.get_mime_by_extension($path) );

			$this->output->set_output(file_get_contents($path));
		}
	}

	function index()
	{
		if($this->session->userdata('i18n_manager') !== FALSE){
			$this->menu();
		}else{
			$this->load->view('login_language_editor',array(
				'styles' => $this->styles,
				'scripts' => $this->scripts
			));
		}
	}

	function login(){
		$username = $this->input->get_post('username');
		$password = $this->input->get_post('password');

		$this->config->load('language_editor');
		$login = $this->config->item('i18n_users');

		$this->session->unset_userdata('i18n_langsenabled');

		foreach($login as $user=>$userdata){
			if (($user == $username ) && ($userdata['password'] == $password )){
				$this->_start_translation();

				$i18n_session = array('i18n_langsenabled' => $userdata['langs']);
				if (isset($userdata['admin']) && $userdata['admin']== TRUE){
					$i18n_session['i18n_admin'] = TRUE;
				}else{
					$i18n_session['i18n_admin'] = FALSE;
				}
				$this->session->set_userdata( $i18n_session );
			}
		}
		redirect('language_editor');
	}

	function logout(){
		$this->_end_translation();
		redirect('language_editor');
	}

	function _start_translation(){
		$this->session->set_userdata('i18n_manager', array(''));
	}

	function _end_translation(){
		$this->session->unset_userdata('i18n_manager', array(''));
	}

	function _check_langenabled($lang){
		if($lang == '' && $this->session->userdata('i18n_manager') !== FALSE){
			return TRUE;
		}else{
			if($lang == ''){
				redirect('language_editor');
			}
		}
		if (is_array($this->session->userdata('i18n_langsenabled')) && in_array($lang, $this->session->userdata('i18n_langsenabled')) ){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function menu($language	= '', $file = '')
	{
		if($this->_check_langenabled($language)){
			$html	= $this->_show_banner($language, $file);

			if($language)
			{
				$this->session->set_userdata('lang',$language);
				$html .= $this->session->userdata('i18n_messages');
				$this->session->unset_userdata('i18n_messages');

				$html	.= $this->i18n_manager->get_list_files($language);
			}
			else
			{
				$html .= '<h2>List of languages</h2>';
				if($this->session->userdata('i18n_admin')){
					$html .= '<p><a class="newfile" href="'.getRelativePath('/language_editor/new_file').'">New language file</a></p>';
				}
				$html	.= $this->i18n_manager->get_list_languages();
			}

             $this->load->view('html_language_editor', array(
			 	'html' => $html,
				'styles' => $this->styles,
				'scripts' => $this->scripts
			));
		}else{
			$this->load->view('no_perm_language_editor',array(
				'styles' => $this->styles,
				'scripts' => $this->scripts
			));
		}
	}

	function remove($lang){
		if($this->session->userdata('i18n_admin')){
			if($this->input->get('confirm') == 'Y'){
				if (
				 	$this->i18n_manager->remove_files( str_replace('.html', '', $this->input->get('file')) )
				 ){
					$this->session->set_userdata('i18n_messages','<div class="ok">files removed successfully!!</div>');
				 }else{
				 	$this->session->set_userdata('i18n_messages','<div class="error">error removing some files!!</div>');
				 }
			}else{
				$this->session->set_userdata('i18n_messages','<div class="error">confirm first!!!</div>');
			}
		}

		redirect(site_url('language_editor/menu/'.$lang),'location');
	}

	function new_file(){
		if($this->session->userdata('i18n_admin')){
			// load required helper
			$this->load->library('table');
			$this->load->helper('form');

			// set hidden fields
			$this->config->load('language_editor');
			$available_langs = $this->config->item('available_languages');
			$hidden_fields	= array(
				'language'	=> $available_langs[0]
			);

			$content = array('KEY'=>'VALUE');
			// set table header
			$this->table->set_heading('KEY INDEX', 'TEXT VALUE');

			// transform each key into input textbox
			foreach($content as $key => $val)
			{
				$input	= array(
					'name' 		=> $key
					, 'id'		=> $key
					, 'value'	=> $val
				);
				$inputkey = array(
					'value'		=> 'KEY',
					'class'		=> 'newfield',
					'onchange'	=> 'setname(this);'
				);

				$this->table->add_row(form_input($inputkey), ( (strpos($val, '\n') !==FALSE || strlen($val)>70)?form_textarea($input):form_input($input) ) );
			}

			// submit button attributes
			$submit	= array(
				'name'		=> 'submitted'
				, 'value'	=> 'Save'
			);

			$html   = $this->session->userdata('i18n_messages');
			$this->session->unset_userdata('i18n_messages');
			$html	.= $this->_show_banner($available_langs[0]);

			// draw form
			$html.= '<div><a href="#" id="add_key">Add KEY & VALUE</a></div>';
			$html 	.= form_open($this->i18n_manager->_process_controller, array('id'=>'save_items'), $hidden_fields);
			$html   .= '<div>'.form_label('FILENAME (WITH PATH):', 'file').form_input(array('name'=>'file', 'value'=>'RELATIVEPATH/FILENAME_WHITHOUT_LANG_SUFFIX')).'</div>';
			// draw table
			$html	.= $this->table->generate();
			// draw submit button
			$html	.= '<button type="submit">Save</button> <button type="reset" id="reset_form">Reset</button>';

			// draw closing form tag
			$html	.= form_close();

			$this->load->view('html_language_editor', array(
				'html' => $html,
				'scripts'=>$this->scripts,
				'styles'=>$this->styles
			));
		}else{
			redirect(site_url('language_editor'));
		}
	}

	function _recursive_edit($key, $val){
		if (is_array($val) || is_object($val)){
			foreach ($val as $k => $v){
				$this->_recursive_edit($key.'/'.$k, $v);
			}
		}else{
			if(is_bool($val))
			{
				$val = $val == FALSE ? 'FALSE' : 'TRUE';
			}
			$input	= array(
					'name' 		=> $key
					, 'id'		=> $key
					, 'value'	=> $val
				);
			$this->table->add_row(
					'<a href="javascript:void(0);" title="try with Google Translator" onclick="gtranslate(this)"><img src="data:image/gif;base64,R0lGODlhEAAQANUrAJu81zqa6oyz2aPYioGt2VarVUunzme1jT6Miuf4yGag2XjJTm6wzNDpvK3W+Ze3y0qotz6CuTR7y4i213i6ii6WKNvo7bbW62Oa2pvK+oWy58jf8VSRuVCmTM7i9FSR2+Dt8o7Hjbrfuy53yIrKdz13uSVxxkSgOr7a9er0/////////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAACsALAAAAAAQABAAAAaOQJVwSByujipOJFJqOpuq40oFSFmvV0IUCUB5v1/BdvpIKbDZseoBRl0oHQkGJKWaraJCQ7WZILZUXx5/CSQBBw0nUSoCdxoOCQMQBgskJIqMXxIOEwwBlAUDmAACBAQjFp6fBSeiRSohAyQHEAEmCAeKUkgnCQsVJiYZJmO7KieWDB/Eu83HwczNzkZSQQA7" /></a>'
					.'<a href="javascript:void(0);" title="revert to original" onclick="gtranslate_revert(this)"><img src="data:image/gif;base64,R0lGODlhEAAQAMQfALnik2aXQv7+/dPut73llbfala3LmW6gSWqdQ2eYRGqaSLfck568iYrUQN7yzF6RPLTXlYjUP8XwmYfQQLbYl4jRQGiaQsPumNbyu7nglNPzsLXYlf7+/lCHK////////yH5BAEAAB8ALAAAAAAQABAAAAV14CeOXumJ5Uh6ShAo5XOqnINgA4YdRpeKHE9nAABUGguMb1bqMDpQyiRC8HGAQoupcyFklqSEJwgtg03otOnTNFs94lVHc5EsPRYrSiiJTChlTz9NEgsNFUUQPgJyBgcFBRsQCg5XKh4yLC4wM5dMayqhoqMhADs%3D" /></a>'
					.'<a href="javascript:void(0);" title="remove this tag" onclick="remove_me(this)"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK8AAACvABQqw0mAAAABh0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzT7MfTgAAAjdJREFUOI2lkktsTHEUxn/nf+/M6IjUq4JUyHgsBOl4NEKiRCRExcaCSEREIhZdaMOKVnVJJVZ0x9LCRnRjQS0Qj4QwoR4xiEZKlYiOeztz57PoDNPBQpzkLP7f+c53Xn+TxP+Yq3y83rBy26umdNffyNmm9MHs+hWHxoGSkMTzTY07Mw0L9WjxPD1ds/RKGS97/7qGzsdL5+vxkpT6m9LdutFjkjBJPNmyNpl7NzhSGAkA8OIeiam1vctuPWoGyKxLdwbDX9sLuVJ8QoLYpGRjw53MPZPE1eRES8ycchJnbQAUi7iYT3Ja7UUX9zPfh750jeZCzLly233ko+amt+9HrHKJVxfM6ZbUaoCKwk/4OM8jzAU45xBg0PcxEd+0+8mrAoBVX+FySaRUCQnMGYwlXxuuqdm8N/MiX+b/JgBwcX79bcTqKvhzcnrdjG13HxQqQVdF4kKq/nhY1KpQosqnDA99uFTNH9dBT6q+06T2nycukyoShPUeuPmymdkTfgmcWTQPy+ePIzoqk83sisHTonS4UgSj13x/R8uLN4EDiMySgdQRIMoeor6h2uT2luy7I6PG6e8VsUDaGkTRsp87aH2Wzcnz94UYoSDErrekl2888fB5EeBwdqAtj50KBaEg77xzu1Jz7wOYN9bU5AiiPbPqWic5W3V+8NPRkUJU8CABEEEEfNk/q+5YzDn/7MBgGzAq6atJwjfzgFhBChibvcYDv3LsCCTpWyke9yAqSNEf/8G/2A9gpCdJVtr5yQAAAABJRU5ErkJggg%3D%3D" /></a>'
					.'<a href="javascript:void(0);" title="switch beetween xhtml/plain text" onclick="switch_editor(this)"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHlSURBVBgZpcE7a1RRFIbh95yZXEaSSLwW
FkFEkICKhWhhIV7AxlKsbSz9DQpa+gfsbERQsUhnEYOFFiJoYSrBO6IBY5I5c2bvtfb6jCIIYjfP
U0liFDUjqhlR99r9FfEfHoFZkNwxg9ZFm5xkTptFY0HbOl02Hdvf4y/hIUoRHsKLMBcWgZkwD6wE
2YNbi1/p8sf6wCkBHsJLkIswD8xF9iB5IZtIHmQLtk11aftOl03nDk/x6NUGpw9OsTYo3H26yoXj
s/TGK8Qmwav3A5aW17h0cjfJg9tL34jWqJM7gxTMTnWIgImxmjYXeuMVNxe+UAFX731kbuc483t6
7Nk+zt5dk7QWROPUTXKevWk4um8LD5+vMjlWcfnMTrqdin4qCGhSIQJOHJjhl41hIVlBTaHut+LU
/DSPX69z9tAMgxTcePCZZKIZFiRohoWQePmuz4eVhARDE5Ey9VqbsSKeLK/TqSsk6CdHEk0qIGhy
IQQ3Fz7xY+Bs7XW4fnEOJVGdvr6s80dm+fQ9kS1IHiQT2YPkQfbAPDAXVgIrwkPM7Zhg8c5buusb
TpsL05Md8ljFpFXYhHCvMK+xEFZEKYEHlAgkkPit2nflhYatIxORAmVHFigVyIFKIAvkggj+VUli
FDUj+gngimmFTeOsxAAAAABJRU5ErkJggg==" /> </a>'
					.form_label($key, $key),
					( (strpos($val, '\n') !==FALSE || strlen($val)>70)?form_textarea($input):form_input($input) )
					 //añadir enlaces para gtranslator http://www.greywyvern.com/code/php/binary2base64
					);
		}
	}

	function edit($language, $file='')
	{
		if($this->_check_langenabled($language)){
			if ($this->input->get('file')){
				$file = str_replace('.html','', $this->input->get('file'));
			}

			// load required helper
			$this->load->library('table');
			$this->load->helper('form');

			// get the content of the file we edit
			$content	= $this->i18n_manager->get_language_file_content($language, $file . '_lang.php');

			// set hidden fields
			$hidden_fields	= array(
				'language'	=> $language
				, 'file'	=> $file
			);

			// set table header
			$this->table->set_heading('KEY INDEX', 'TEXT VALUE');

			// transform each key into input textbox
			foreach($content as $key => $val)
			{
				$this->_recursive_edit($key, $val);
			}

			// submit button attributes
			$submit	= array(
				'name'		=> 'submitted'
				, 'value'	=> 'Save'
			);

			$html   = $this->session->userdata('i18n_messages');
			$this->session->unset_userdata('i18n_messages');
			$html	.= $this->_show_banner($language, $file);
			// draw form
			$html.= '<div><a href="#" id="add_key">Add KEY & VALUE</a></div>';
			$html 	.= form_open($this->i18n_manager->_process_controller, array('id'=>'save_items'), $hidden_fields);
			// draw table
			$html	.= $this->table->generate();
			// draw submit button
			$html	.= '<button type="submit" class="button_mid">Save</button> <button type="reset" id="reset_form"  class="button_mid">Reset</button>';

			// draw closing form tag
			$html	.= form_close();

			$this->load->view('html_language_editor', array(
				'html' => $html,
				'scripts'=>$this->scripts,
				'styles'=>$this->styles
			));
		}else{
			$this->load->view('no_perm_language_editor');
		}
	}


	function eipsave()
	{
		if($this->_check_langenabled($language)){
			$json_err	= array();

			$language	= $this->input->post('language');
			$line		= $this->input->post('line', TRUE);
			$text 		= $this->input->post('value', TRUE);

			if($language && $line && $text)
			{
				if((list($file, $content) = $this->i18n_manager->find_file($language, $line)) !== false)
				{
					$content[$line]	= $text;

					if($this->i18n_manager->put_content($language, $file, $content))
					{
						$json_err	= array(
							'errorCode'	=> 0
							, 'value'	=> $text
						);
					}
					else
					{
						$json_err	= array(
							'errorCode'	=> 1
							, 'errorMsg'	=> 'error: cannot save'
						);
					}
				}
				else
				{
					$json_err	= array(
						'errorCode'	=> 1
						, 'errorMsg'	=> 'error: file not found'
					);
				}
			}
			else
			{
				$json_err	= array(
					'errorCode'	=> 1
					, 'errorMsg'	=> 'error: info missing'
				);
			}

			exit(json_encode($json_err));
		}else{
			$this->load->view('no_perm_language_editor');
		}
	}

	function _save_recursive($path, $value){
		if (strpos( $path, '/') === FALSE){
			return array(( (is_numeric($path))?( (int)$path ):$path ) => $value);
		}else{
			$paths = explode('/', $path);
			return array($paths[0]=> $this->_save_recursive( str_replace($paths[0].'/', '', $path), $value ) );
		}
	}

	function save()
	{
		if($this->_check_langenabled($this->input->post('language', ''))){
			$this->load->helper('i18n');

			$language	= $this->input->post('language', TRUE);
			$file		= $this->input->post('file', TRUE);

			$array = array();
			foreach($_POST as $field => $value)
			{
				if(in_array($field, array('language', 'file', 'submitted')))
					continue;

				$post_val = preg_replace('/%\/((\d|[a-f,A-F]){2})/','%$1', $this->input->post($field, TRUE));

				$array = array_extend($array, $this->_save_recursive($field, $post_val));
			}


			if($this->i18n_manager->put_content($language, $file, $array))
			{
				$this->session->set_userdata('i18n_messages','<div class="ok">File saved successfully!</div>');
			}else{
				$this->session->set_userdata('i18n_messages','<div class="error">Error saving the file!</div>');
			}

			if($this->input->server('HTTP_REFERER')!==FALSE){
				redirect($this->input->server('HTTP_REFERER'));
			}else{
				redirect(site_url('language_editor'));
			}
		}else{
			$this->load->view('no_perm_language_editor');
		}
	}


	function _show_banner($language = "", $file = "")
	{
		$link 	= '';
		$html	= '';
		if($language)
		{
			$html	= "<h2>Editing language: " . $language . "</h2>";
			$link	= "<a href=\"" . site_url($this->i18n_manager->_base_controller) . "\">Back to language list</a><br />";

		}
		if($file)
		{
			$html	.= "<h1>Editing language: " . $this->i18n_manager->_files[$language][$file . '_lang.php'] . "</h1>";
			$link	.= "<a href=\"" . site_url($this->i18n_manager->_menu_controller . $language) . "\">Back to File list</a><br />";
		}

		return $html . $link . '<br />';

	}

	/**
	 * Indents a flat JSON string to make it more human-readable
	 *
	 * @param string $json The original JSON string to process
	 * @return string Indented version of the original JSON string
	 */
	function _indent($json) {

	    $result    = '';
	    $pos       = 0;
	    $strLen    = strlen($json);
	    $indentStr = '  ';
	    $newLine   = "\n";

	    for($i = 0; $i <= $strLen; $i++) {

	        // Grab the next character in the string
	        $char = substr($json, $i, 1);
			$previous_char = substr($json, $i-1, 1);

	        // If this character is the end of an element,
	        // output a new line and indent the next line
	        if($char == '}' || $char == ']') {
	            $result .= $newLine;
	            $pos --;
	            for ($j=0; $j<$pos; $j++) {
	                $result .= $indentStr;
	            }
	        }

	        // Add the character to the result string
	        $result .= $char;

	        // If the last character was the beginning of an element,
	        // output a new line and indent the next line
	        if (($char == ',' && $previous_char == '"' ) || $char == '{' || $char == '[') {
	            $result .= $newLine;
	            if ($char == '{' || $char == '[') {
	                $pos ++;
	            }
	            for ($j = 0; $j < $pos; $j++) {
	                $result .= $indentStr;
	            }
	        }
	    }

	    return $result;
	}

	/**
	 * Gets the line of language file
	 * @return JSON with the result
	 */
	function get_line(){
		$line = $this->input->get_post("line");
		$file = $this->input->get_post("file");
		$language = $this->input->get_post("language");

		$langs = $this->i18n_manager->get_language_file_content($language, $file. '_lang.php');

		$lang_e = array();
		foreach($langs as $key => $value)
		{
			$a = array();
			$this->i18n_manager->put_recursive($key, $value, $a);
			$lang_e = array_merge($lang_e, $a);
		}

		$this->output->set_ajax_response( array('result'=> ( (isset($lang_e[$line]))?$lang_e[$line]:'NOT FOUND THIS TAG' )) );
	}
}

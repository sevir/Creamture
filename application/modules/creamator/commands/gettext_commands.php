<?php if ( ! defined('CREAMATOR')) exit('Functions only for Creamator Manager');

/* Functions definition */
$creamator['GetText Commands'] = array(
	'generateGettext' => array(
		'description'=>'Generate all GetText files from PHP files of the proyect',
		'usage' => 'creamator generateGettext <directory>',
		'parameters' => array('directory must be a path of the CI project'),
		'num_parameters' => 1
	),
    'compileGettext' => array(
        'description'=>'Compile mo files',
        'usage' => 'creamator compileGettext',
        'num_parameters' => 0
    ),
    'generateTwigGettext' => array(
        'description'=>'Generate preprocessed files of Twig Templates for Gettext',
        'usage' => 'creamator generateTwigGettext <module name>',
        'parameters' => array('Optional module name'),
        'num_parameters' => 0
    )
);

function generateTwigGettext($module=NULL){
    $CI = & get_instance();
    $CI->config->load('language');

    $CI->load->add_package_path(APPPATH.'third_party/twig/');

    if($module)
        $CI->load->library('twig', array('debug'=>true, 'template_dir'=>APPPATH.'modules/'.$module.'/views'));
    else
        $CI->load->library('twig', array('debug'=>true, 'template_dir'=>APPPATH.'views'));

    $CI->twig->generate_gettext($CI->config->item('twig_tmp_locale_dir'));

    println('Twig preprocessed files generated in: '.$CI->config->item('twig_tmp_locale_dir').' folder');
}

function generateGettext($directory){
    $CI = & get_instance();
    $CI->config->load('language');

    $language = $CI->config->item('default_locale');

    println('Generating php file list');

    $path_tmp_files = $CI->config->item('locale_dir').'../';
	$result = directoryToArray($directory, true, false, true, '/cache|logs/', '/.*(\/|\\\\)(application|i18n)((\/|\\\\)).*php/');

	$CI->load->helper('file');

	write_file($path_tmp_files.'/phpfiles.txt', implode("\n", $result));

    println('Generated php list file in '.$path_tmp_files.' folder'); 

    println('generating POT file');
    $output = array();
    exec(
        'xgettext -k__ -k_ -kgettext --from-code UTF-8 -d '.$CI->config->item('domain').
        ' -o '.$CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot'.
        ' -L PHP --no-wrap -f '.$path_tmp_files.'/phpfiles.txt',
        $output,
        $result
        );
    echo(implode("\n", $output));

    $potfile = file_get_contents($CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot');
    $potfile = str_replace('PACKAGE VERSION', '1', $potfile);
    $potfile = str_replace('LANGUAGE <LL@li.org>', $language.' <lang@creamture.com>', $potfile);
    $potfile = str_replace('FULL NAME <EMAIL@ADDRESS>', 'Creamture Translator <lang@creamture.com>', $potfile);
    $potfile = str_replace('"Language: \n"', '"Language: es\n"', $potfile);

    write_file($CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot', $potfile);

    unlink($path_tmp_files.'/phpfiles.txt');

    println('Initial POT file in '.$path_tmp_files.' folder');

    $lang_folders = $CI->config->item('available_locales');
    $last_po_files = directoryToArray($CI->config->item('locale_dir'), true, true, true, '', '/\.po/');

    foreach ($lang_folders as $lang_folder) {
        $output = array();

        if(substr_in_array($last_po_files, $lang_folder) === FALSE){
            //initial po file
            exec(
                'msginit --no-translator -l '.$lang_folder.' -o '.$CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.po -i '.
                $CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot',
                $output,
                $result
                );
            echo(implode("\n", $output));
        }else{
            //merge new lang lines with old
            exec(
                'msgmerge -U '.$CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.po '.
                $CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot',
                $output,
                $result
                );
            echo(implode("\n", $output));
        }
    }

    unlink($CI->config->item('locale_dir').'../'.$CI->config->item('domain').'.pot');

    //if origin lang is different to english copy english translation
    if ( preg_match('/en/', $language) == false){

    	$english_base = substr_in_array($lang_folders, 'en_'); $english_base = array_pop($english_base);

    	$english_po = file_get_contents(
            $CI->config->item('locale_dir').$english_base.'/LC_MESSAGES/'.$CI->config->item('domain').'.po'
            );
		$english_po = str_replace('English', 'Spanish', $english_po);
		$english_po = str_replace('en_UK', 'es', $english_po);
		write_file(
            $CI->config->item('locale_dir').$language.'/LC_MESSAGES/'.$CI->config->item('domain').'.po',
            $english_po
        );
    }    

    println('Generated po files for locales: '.implode(',',$lang_folders));

    //msgfmt -cv -o /path/to/output.mo /path/to/input.po
    foreach ($lang_folders as $lang_folder) {
        $output = array();

        if(substr_in_array($last_po_files, $lang_folder) === FALSE){
            //compile mo file
            exec(
                'msgfmt -cv -o '.$CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.mo '.
                $CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.po',
                $output,
                $result
                );
            echo(implode("\n", $output));
        }
    }

    println('Compiled mo files');
}

function compileGettext(){
    $CI = & get_instance();
    $CI->config->load('language');

    $lang_folders = $CI->config->item('available_locales');
    $last_po_files = directoryToArray($CI->config->item('locale_dir'), true, true, true, '', '/\.po/');

    foreach ($lang_folders as $lang_folder) {
        $output = array();

        if(substr_in_array($last_po_files, $lang_folder) !== FALSE){
            //compile mo file
            exec(
                'msgfmt -cv -o '.$CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.mo '.
                $CI->config->item('locale_dir').$lang_folder.
                '/LC_MESSAGES/'.$CI->config->item('domain').'.po',
                $output,
                $result
                );
            println(implode("\n", $output));
        }
    }

    println('Compiled mo files');
}



/**
 * Get an array that represents directory tree
 * @param string $directory     Directory path
 * @param bool $recursive         Include sub directories
 * @param bool $listDirs         Include directories on listing
 * @param bool $listFiles         Include files on listing
 * @param regex $exclude         Exclude paths that matches this regex
 */
function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '', $include = '/.*/') {
    $arrayItems = array();
    $skipByExclude = false;
    $forceByInclude = true;
    $handle = opendir($directory);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
        preg_match('/(^(([\.]){1,2})$|(\.(svn|git|gitignore|md))|(Thumbs\.db|\.DS_STORE))$/iu', $file, $skip);
        if($exclude){
            preg_match($exclude, $directory . DIRECTORY_SEPARATOR . $file, $skipByExclude);
        }
        if($include){
        	preg_match($include, $directory . DIRECTORY_SEPARATOR . $file, $forceByInclude);
        }
        if (!$skip && !$skipByExclude) {
            if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                if($recursive) {
                    $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude, $include));
                }
                if($listDirs && $forceByInclude){
                    $file = $directory . DIRECTORY_SEPARATOR . $file;
                    $arrayItems[] = $file;
                }
            } else {
                if($listFiles && $forceByInclude){
                    $file = $directory . DIRECTORY_SEPARATOR . $file;
                    $arrayItems[] = $file;
                }
            }
        }
    }
    closedir($handle);
    }
    return $arrayItems;
}

function substr_in_array($haystack, $needle){
    $found = array();
    // cast to array 
    $needle = (array) $needle;
    // map with preg_quote 
    $needle = array_map('preg_quote', $needle);
    // loop over  array to get the search pattern 
    foreach ($needle as $pattern)
    {
        if (count($found = preg_grep("/$pattern/", $haystack)) > 0) {
            return $found;
        }
    }
    // if not found 
    return false;
}
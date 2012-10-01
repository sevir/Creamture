<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**  * translate Function
  *
  * @param    string   Text which should be translated
  * @param    array    Params with Vars which should be replaced (enclosed with {})
  *
  * @return   string   Translated Text
  */

$GLOBALS['gettext_loaded'] = FALSE;
function __($msgid, array $params = array()) {
  if (!$GLOBALS['gettext_loaded']){
    loadGettext();
    $GLOBALS['gettext_loaded'] = TRUE;
  }
  
  $trans = _($msgid); // Native PHP Function    

  if (!count($params)) {
    return $trans;
  }    

  foreach (array_keys($params) as $element) {
    $search[] = '{' . $element . '}';
  }        

  return str_replace($search, $params, $trans);
}

function loadGettext(){
    $CI = & get_instance();
    $CI->config->load('language');

    $language = $CI->config->item('default_locale','language');
    putenv('LC_ALL='.$language);
    setlocale(LC_ALL, $language.'.'.$CI->config->item('encoding'));

    // Set the text domain as 'messages'
    $domain = $CI->config->item('domain');
    bindtextdomain($domain, $CI->config->item('locale_dir'));
    // bind_textdomain_codeset is supported only in PHP 4.2.0+
    if (function_exists('bind_textdomain_codeset'))
    bind_textdomain_codeset($domain, 'UFT-8');
    textdomain($domain);
}
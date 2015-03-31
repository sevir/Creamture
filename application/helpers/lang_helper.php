<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Auto-detect browser language
 */
load_gettext('auto');

/**
 * Set language
 *
 * @param   string  $language   Values: 'auto' | 'xx_YY' | 'xx_YY.utf8'
 */
function load_gettext($language)
{
  $CI = & get_instance();
  $CI->config->load('language_conf', TRUE);

  $locales_dir = $CI->config->item('locale_dir', 'language_conf');
  $domain = $CI->config->item('domain','language_conf');  

  if( ! $CI->input->is_cli_request())
  {
    // Try auto-detection if asked for. Set default language if it fails
    if($language === 'auto')
    {
      $CI->load->library('session');
      if ($CI->session->userdata('locale')) {
        $language = $CI->session->userdata('locale');
      } else {
        $language = $CI->lang->autodetect();
        $CI->session->set_userdata('locale', $language); 
      }
    }
  }

  if ($language && ! in_array($language, $CI->config->item('available_locales','language_conf')) ){
    foreach ($CI->config->item('language_aliases','language_conf') as $alias => $aliases) {
      if ( sub_in_array($language, $aliases, TRUE) === 0 ){
        $language = $alias;
        break;
      }
    }
  }

  if(!$language || ! in_array($language, $CI->config->item('available_locales','language_conf'))){
    $language = $CI->config->item('default_locale','language_conf');
    if ( ! $CI->input->is_cli_request() )
      $CI->session->set_userdata('locale', $language);
  }

  putenv('LANGUAGE='.$language);
  putenv('LANG='.$language);
  putenv('LC_ALL='.$language);
  putenv('LC_MESSAGES='.$language);
  setlocale(LC_ALL,$language);
  setlocale(LC_CTYPE,$language);
  //fix numeros
  putenv('LC_NUMERIC='.'en_US');
  setlocale(LC_NUMERIC,'en_US');  

  bindtextdomain($domain,realpath($locales_dir));
  bind_textdomain_codeset($domain, $CI->config->item('encoding','language_conf'));
  textdomain($domain);
}

/**
 * Similar to in_array but searching substrings
 * @param string    Text to find
 * @param array     Array to search into
 * @param inverse   Search $needle in the $haystack elements or search the elements substring in needle
 */
function sub_in_array($needle, $haystack, $inverse=FALSE){
  foreach ($haystack as $v) {
    if( ($r = ($inverse)?strpos($needle, $v):strpos($v, $needle) ) !== FALSE)
      return $r;
  }
  return false;
}


/**
 * Translate literal with parameters
 *
 * @param    string   Text which should be translated
 * @param    array    Params with Vars which should be replaced (enclosed with {})
 *
 * @return   string   Translated Text
 */
function __($msgid, array $params = array())
{
  $trans = _($msgid); // Native PHP Function    

  if ( ! count($params))
  {
    return $trans;
  }

  foreach (array_keys($params) as $element)
  {
    $search[] = '{' . $element . '}';
  }

  return str_replace($search, $params, $trans);
}

/* EOF */
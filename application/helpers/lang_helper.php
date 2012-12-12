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

  if( ! $CI->input->is_cli_request())
  {
    $CI->config->load('language');
    $CI->load->library('session');

    $locales_dir = $CI->config->item('locale_dir');
    $domain = $CI->config->item('domain');

    // Try auto-detection if asked for. Set default language if it fails
    if($language === 'auto')
    {
      if ($CI->session->userdata('locale')) {
        $language = $CI->session->userdata('locale');
      } else {
        $language = $CI->lang->autodetect();
        $language = $language ? $language : $CI->config->item('default_locale','language');
        $CI->session->set_userdata('locale', $language);
      }
    }

    putenv('LANGUAGE='.$language);
    putenv('LANG='.$language);
    putenv('LC_ALL='.$language);
    putenv('LC_MESSAGES='.$language);
    setlocale(LC_ALL,$language);
    setlocale(LC_CTYPE,$language);

    bindtextdomain($domain,$locales_dir);
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
  }
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
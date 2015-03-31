<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Lang extends MX_Lang
{
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Autodetect language based on user's browser
     */
    public function autodetect()
    {
        $locale = FALSE;

        // get an array of all accepted languages of the client

        $httplanguages = getenv('HTTP_ACCEPT_LANGUAGE');
        $languages = array();

        if ($httplanguages)
        {
            $accepted = preg_split('/,\s*/', $httplanguages);

            foreach ($accepted as $accept)
            {
                $match  = NULL;
                $result = preg_match('/^([a-z]{1,8}(?:[-_][a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',$accept, $match);

                if ($result < 1)
                {
                    continue;
                }

                if (isset($match[2]) === TRUE)
                {
                    $quality = (float) $match[2];
                }
                else
                {
                    $quality = 1.0;
                }

                $countrys = explode('-', $match[1]);
                $region   = array_shift($countrys);

                $country2 = explode('_', $region);
                $region   = array_shift($country2);

                foreach ($countrys as $country)
                {
                    $languages[$region . '_' . strtoupper($country)] = $quality;
                }

                foreach ($country2 as $country)
                {
                    $languages[$region . '_' . strtoupper($country)] = $quality;
                }

                if ((isset($languages[$region]) === false) || ($languages[$region] < $quality))
                {
                    $languages[$region] = $quality;
                }
            }

            $locale = key($languages);
        }

        return $locale;
    }

    /**
     * Get all languages
     */
    public function get_all()
    {
    	return $this->language;
    }
    
}
/* EOF */
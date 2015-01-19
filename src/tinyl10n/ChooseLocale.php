<?php

/* ChooseLocale
 *
 * Licence: MPL 2/GPL 2.0/LGPL 2.1
 * Author: Pascal Chevrel, Mozilla <pascal@mozilla.com>, Mozilla
 * Contributor: Stanislaw Malolepszy <stas@mozilla.com>, Mozilla
 * Date : 2012-12-09
 * version: 0.6
 * Description:
 * Class to choose the locale we will show to the visitor
 * based on http accept-lang headers and our list of supported locales.
 *
*/

namespace tinyl10n;

class ChooseLocale
{
    public    $HTTPAcceptLang;
    public    $supportedLocales;
    protected $detectedLocale;
    protected $defaultLocale;
    public    $mapLonglocales;
    public    $rtl = ['ar', 'fa', 'he', 'ur'];

    public function __construct($list = ['en-US'])
    {
        $this->HTTPAcceptLang   = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $this->supportedLocales = array_unique($list);
        $this->setDefaultLocale('en-US');
        $this->setCompatibleLocale();
        $this->mapLonglocales = true;
    }

    public function getAcceptLangArray()
    {
        if (empty($this->HTTPAcceptLang)) {
            return null;
        } else {
            return explode(',', $this->HTTPAcceptLang);
        }
    }

    public function getCompatibleLocale()
    {
        $acclang = $this->getAcceptLangArray();

        if (!is_array($acclang)) {
            return $this->defaultLocale;
        }

        foreach ($acclang as $var) {
            $locale      = $this->cleanHTTPlocaleCode($var);
            $shortLocale = explode('-', $locale)[0];

            if (in_array($locale, $this->supportedLocales)) {
                return $locale;
            }

            if (in_array($shortLocale, $this->supportedLocales)) {
                return $shortLocale;
            }

            // Check if we map visitors short locales to site long locales like en->en-GB
            if ($this->mapLonglocales == true) {
                foreach ($this->supportedLocales as $supported) {
                    $shortSupportedLocale = explode('-', $supported)[0];
                    if ($shortLocale == $shortSupportedLocale) {
                        return $supported;
                    }
                }
            }
        }

        return $this->defaultLocale;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function getDetectedLocale()
    {
        return $this->detectedLocale;
    }

    public function setCompatibleLocale($locale=false)
    {
        if ($locale && in_array($locale, $this->supportedLocales)) {
            $this->detectedLocale = $locale;
        } else {
            $this->detectedLocale = $this->getCompatibleLocale();
        }

        return $this;
    }

    public function setDefaultLocale($locale)
    {
        /*
         * The default locale should always be among the site locales
         * if not, the first locale in the supportedLocales array is default
         */
        if (!in_array($locale, $this->supportedLocales)) {
            $this->defaultLocale = $this->supportedLocales[0];
        } else {
            $this->defaultLocale = $locale;
        }

        return $this;
    }

    public function getDirection()
    {
        return in_array($this->detectedLocale, $this->rtl) ? 'rtl' : 'ltr';
    }

    private function cleanHTTPlocaleCode($str)
    {
        return trim(explode(';', $str)[0]);
    }
}

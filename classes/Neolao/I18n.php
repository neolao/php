<?php
namespace Neolao;

use \Neolao\I18n\Locale;

/**
 * Internationalization
 */
class I18n
{
    /**
     * Default locale string
     *
     * @var string
     */
    public $defaultLocaleString;

    /**
     * Current locale string
     *
     * @var string
     */
    public $localeString;

    /**
     * Locales
     *
     * @var array
     */
    protected $_locales;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defaultLocaleString = null;
        $this->localeString = null;
        $this->_locales = [];
    }

    /**
     * Add a locale
     *
     * @param   \Neolao\I18n\Locale     $locale     Locale instance
     */
    public function addLocale(Locale $locale)
    {
        $localeString = $locale->localeString;

        $this->_locales[$localeString] = $locale;
        $this->localeString = $localeString;
    }

    /**
     * Indicates that the specified locale exists
     *
     * @param   string      $localeString       Locale string
     */
    public function hasLocale($localeString)
    {
        if (array_key_exists($localeString, $this->_locales)) {
            return true;
        }
        return false;
    }

    /**
     * Remove all locales
     */
    public function removeLocales()
    {
        $this->defaultLocaleString = null;
        $this->localeString = null;
        $this->_locales = [];
    }

    /**
     * Get a message from a key
     *
     * @param   string      $key            Message key
     * @param   array       $parameters     Parameters
     * @return  string                      Message value
     */
    public function getMessage($key, $parameters = [])
    {
        // If the locale does not exist, get the first
        $localeString = $this->localeString;
        if (!array_key_exists($localeString, $this->_locales)) {
            foreach ($this->_locales as $locale) {
                break;
            }
            if (empty($locale)) {
                return $key;
            }
        } else {
            $locale = $this->_locales[$localeString];
        }

        // Get the message from the locale
        $message = $locale->getMessage($key, $parameters);

        // If there is no message ($key == $message), then fallback the default locale
        // @todo

        return $message;
    }
}

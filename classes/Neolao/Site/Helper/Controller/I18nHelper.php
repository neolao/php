<?php
namespace Neolao\Site\Helper\Controller;

use \Neolao\I18n;

/**
 * I18n helper
 */
class I18nHelper extends AbstractHelper
{
    /**
     * Internationalization instance
     *
     * @var \Neolao\I18n
     */
    public $i18n;

    /**
     * Get a translated message
     * (access point of the helper)
     *
     * @param   string  $key        The message key
     * @param   array   $parameters The message parameters
     * @return  string              The translated message
     */
    public function main($key, $parameters = [])
    {
        if ($this->i18n instanceof I18n === false) {
            return $key;
        }

        // Return the message
        return $this->i18n->getMessage($key, $parameters);
    }
}

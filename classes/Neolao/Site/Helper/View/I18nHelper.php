<?php
namespace Neolao\Site\Helper\View;

use \Neolao\Site\Helper\View\AbstractHelper;
use \Neolao\I18n;

/**
 * Helper to get a translated message
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
     * (access point of the view helper)
     *
     * @param   string  $key        The message key
     * @return  string              The translated message
     */
    public function main($key)
    {
        if ($this->i18n instanceof I18n === false) {
            return $key;
        }

        // Get the key
        $array = explode(',', $key);
        $key = array_shift($array);

        // Build parameters
        // @todo Handle errors
        $parameters = [];
        foreach ($array as $parameter) {
            list($name, $value) = explode(':', $parameter);
            $name               = trim($name);
            $value              = trim($value);
            $parameters[$name]  = $value;
        }

        // Return the message
        return $this->i18n->getMessage($key, $parameters);
    }


}

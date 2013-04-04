<?php
namespace Neolao\I18n;

/**
 * Locale
 */
class Locale
{
    /**
     * Locale string
     *
     * @var string
     */
    public $localeString;

    /**
     * Messages
     *
     * @var array
     */
    protected $_messages;

    /**
     * Constructor
     *
     * @param   string      $string     Locale string
     */
    public function __construct($string = 'en_US')
    {
        $this->localeString = $string;
        $this->_messages = [];
    }

    /**
     * Add messages from a json string
     *
     * @param   string      $json       Json string
     */
    public function addMessagesJson($json)
    {
        // Decode the json string if necessary
        if (is_string($json)) {
            $json = json_decode($json);
        }

        // Populate the messages
        foreach ($json as $key => $value) {
            $this->_messages[$key] = $value;
        }
    }

    /**
     * Get the message value from a key
     *
     * @param   string      $key            Message key
     * @param   array       $parameters     Parameters
     * @return  string                      Message value
     */
    public function getMessage($key, $parameters = [])
    {
        $message = $key;

        if (array_key_exists($key, $this->_messages)) {
            $message = $this->_messages[$key];
        }

        if (count($parameters) > 0) {
            $search = [];
            $replace = [];
            foreach ($parameters as $name => $value) {
                $search[] = '{{' . $name . '}}';
                $replace[] = $value;
            }
            $message = str_replace($search, $replace, $message);
        }

        return $message;
    }
}

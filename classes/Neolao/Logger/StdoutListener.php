<?php
namespace Neolao\Logger;

use \Neolao\Logger\ListenerInterface;

/**
 * stdout listener for the logger
 */
class StdoutListener implements ListenerInterface
{
    /**
     * Level
     *
     * @var string
     */
    protected $_level;


    /**
     * Constructor
     *
     * @param   string      $level          The filtered level
     */
    public function __construct($level = null)
    {
        $this->_level = $level;
    }

    /**
     * Log a message
     *
     * @param   string      $level      The level
     * @param   string      $message    The message
     */
    public function log($level, $message)
    {
        // Skip if the levels do not match
        if (!is_null($this->_level) && $this->_level !== $level) {
            return;
        }

        // Add the date and new line
        $message = date('[Y/m/d H:i:s] ').$message."\n";

        echo $message;
    }

}

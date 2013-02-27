<?php
/**
 * @package Neolao
 */
namespace Neolao;


use \Neolao\Logger\ListenerInterface;
use \Psr\Log\AbstractLogger;

/**
 * Logger
 */
class Logger extends AbstractLogger
{
    use \Neolao\Mixin\Singleton;

    // Message levels
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    /**
     * Listeners
     *
     * @var array
     */
    protected $_listeners;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->_listeners = [];
    }

    /**
     * Add a listener
     */
    public function addListener(ListenerInterface $listener)
    {
        $this->_listeners[] = $listener;
    }

    /**
     * Logs with an arbitrary level
     *
     * @param   string      $level      The level
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function log($level, $message, array $context = [])
    {
        // Generate the message with the context
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{'.$key.'}'] = $value;
        }
        $generatedMessage = strtr($message, $replace);

        // Execute the log method on each listener
        foreach ($this->_listeners as $listener) {
            $listener->log($level, $generatedMessage);
        }
    }
}


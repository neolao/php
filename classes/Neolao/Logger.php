<?php
/**
 * @package Neolao
 */
namespace Neolao;


use \Neolao\Logger\ListenerInterface;

/**
 * Logger
 */
class Logger
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
     *
     * @param   ListenerInterface   $listener   Listener instance
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

    /**
     * System is unusable
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function emergency($message, array $context = [])
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function alert($message, array $context = [])
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Critical conditions
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function critical($message, array $context = [])
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically be logged and monitored
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function error($message, array $context = [])
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function warning($message, array $context = [])
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Normal but significant events
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function notice($message, array $context = [])
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Interesting events
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function info($message, array $context = [])
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Detailed debug information
     *
     * @param   string      $message    The message
     * @param   array       $context    The context
     */
    public function debug($message, array $context = [])
    {
        $this->log(self::DEBUG, $message, $context);
    }

}


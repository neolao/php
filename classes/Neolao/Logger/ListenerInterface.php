<?php
namespace Neolao\Logger;

/**
 * Interface of a logger listener
 */
interface ListenerInterface
{
    /**
     * Log a message
     *
     * @param   string      $level      The level
     * @param   string      $message    The message
     */
    function log($level, $message);
}

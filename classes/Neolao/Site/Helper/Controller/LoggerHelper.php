<?php
namespace Neolao\Site\Helper\Controller;

use \Neolao\Logger;

/**
 * Logger helper
 */
class LoggerHelper extends AbstractHelper
{
    /**
     * Get the logger instance
     *
     * @return  \Neolao\Logger          Logger instance
     */
    public function main()
    {
        $logger = Logger::getInstance();
        return $logger;
    }
}

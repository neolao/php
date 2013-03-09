<?php
namespace Neolao\tests\units;

use \mageekguy\atoum;


/**
 * Logger
 */
class Logger extends atoum\test
{
    /**
     * Static method "getInstance"
     */
    public function testGetInstance()
    {
        $this->assert->object(\Neolao\Logger::getInstance())
                     ->isInstanceOf('\\Neolao\\Logger')
                     ->isIdenticalTo(\Neolao\Logger::getInstance());
    }
}

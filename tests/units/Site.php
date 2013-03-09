<?php
namespace Neolao\tests\units;

use \mageekguy\atoum;


/**
 * Basic site
 */
class Site extends atoum\test
{
    /**
     * "serverName" getter/setter
     */
    public function testServerName()
    {
        $site = new \Neolao\Site();
        $site->serverName = 'foo';

        $this->assert->string($site->serverName)->isEqualTo('foo');
            
    }
}

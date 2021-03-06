<?php
namespace Neolao\Site\Helper\Controller;

/**
 * Link helper
 *
 * It is used to create the reverse URL of a route
 */
class LinkHelper extends AbstractHelper
{
    /**
     * Get the reverse URL of a route
     *
     * @param   string  $routeName      The route name
     * @param   array   $parameters     The route parameters
     * @return  string                  The reverse URL
     */
    public function main($routeName, $parameters = [])
    {
        $controller = $this->getController();
        $url        = $controller->site->reverse($routeName, $parameters);
        return $url;
    }
}

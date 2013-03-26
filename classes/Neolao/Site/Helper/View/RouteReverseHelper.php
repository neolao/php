<?php
/**
 * @package Neolao\Site\Helper\View
 */
namespace Neolao\Site\Helper\View;

use \Neolao\Logger;
use \Neolao\Site\Helper\View\AbstractHelper;
use \Neolao\Site\Request;
use \Neolao\Site;

/**
 * Helper to get the reverse URL of a route
 */
class RouteReverseHelper extends AbstractHelper
{
    /**
     * Get the reverse URL of the specified route
     * (access point of the view helper)
     *
     * @param   string  $route      The route
     * @return  string              The reverse URL
     */
    public function main($route)
    {
        // Get the request instance
        $view = $this->getView();
        if ($view->site instanceof Site === false) {
            return 'error';
        }

        // Get the route name
        $routeArray = explode(',', $route);
        $routeName = array_shift($routeArray);
        $routeName = trim($routeName);

        // Get the parameters
        $parameters = [];
        if (count($routeArray) > 0) {
            foreach ($routeArray as $parameter) {
                list($key, $value) = explode(':', $parameter);
                $key = trim($key);
                $value = trim($value);
                $parameters[$key] = $value;
            }
        }

        // Get the reverse URL
        $url = $view->site->reverse($routeName, $parameters);

        return $url;
    }


}

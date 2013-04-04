<?php
namespace Neolao\Site\Router;

use \Neolao\Site\Request;

/**
 * Route with a regexp as pattern
 *
 * Example of a configuration:
 * <pre>
 * $config = [
 *      'pattern'       => '/^\/account\/([a-zA-Z]+)\/order\/([0-9]+)\/pdf$/',
 *      'map'           => ['name', 'orderId'],
 *      'controller'    => 'foo',
 *      'action'        => 'bar',
 *      'reverse'       => '/account/%s/order/%d/pdf'
 * ];
 * </pre>
 */
class RouteRegexp extends RouteAbstract implements RouteInterface
{
    /**
     * Parameter map
     *
     * @var array
     */
    protected $_map;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_map = [];
    }

    /**
     * Configure the route
     *
     * @param   array   $parameters         Parameters
     */
    public function configure(array $parameters)
    {
        parent::configure($parameters);

        if (isset($parameters['map'])) {
            $map = $parameters['map'];
            if (is_object($map)) {
                $array = [];
                foreach ($map as $item) {
                    $array[] = $item;
                }
                $map = $array;
            }
            $this->_map = $map;
        }
    }

    /**
     * Find the route based on the request
     *
     * @param   \Neolao\Site\Request    $request        Request instance
     * @return  bool                                    true if the request is updated and matches the route, false otherwise
     */
    public function handleRequest(Request $request)
    {
        $pathInfo       = $request->pathInfo;
        $routePattern   = $this->_pattern;
        $map            = $this->_map;

        $count = preg_match($routePattern, $pathInfo, $matches);
        if ($count > 0) {
            // Fill controller and action names
            $request->controllerName = $this->_controller;
            $request->actionName = $this->_action;

            // Fill parameter values
            array_shift($matches);
            $paramLength = count($matches);
            for ($paramIndex = 0; $paramIndex < $paramLength; $paramIndex++) {
                $paramValue = $matches[$paramIndex];
                if (isset($map[$paramIndex])) {
                    $paramName = $map[$paramIndex];
                    $request->parameters[$paramName] = $paramValue;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Reverse a route
     *
     * @param   array   $parameters         Parameters
     * @return  string                      Reverse path
     */
    public function reverse(array $parameters = [])
    {
        $result = '';

        // Match parameters with the reverse route
        $map = [];
        $paramLength = count($this->_map);
        for ($index = 0; $index < $paramLength; $index++) {
            $paramName = $this->_map[$index];
            if (isset($parameters[$paramName])) {
                $map[$index] = $parameters[$paramName];
                unset($parameters[$paramName]);
            } else {
                $map[$index] = '';
            }
        }
        $result = vsprintf($this->_reverse, $map);

        // For the rest of parameters, add a query string
        $query = array_merge($parameters);
        if (count($query) > 0) {
            $result .= '?' . http_build_query($query);
        }

        return $result;

    }
}

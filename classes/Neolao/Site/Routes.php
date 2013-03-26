<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site;


/**
 * Routes
 */
class Routes
{
    use \Neolao\Mixin\GetterSetter;

    /**
     * URL Routes
     *
     * @var array
     */
    protected $_routes;

    /**
     * Constructor
     */
    public function __contruct()
    {
        $this->_routes = [];
    }

    /**
     * Configure the routes rules
     * 
     * @param   stdClass   $config     Configuration
     */
    public function configure($config)
    {
        $this->_routes = [];

        // Default controller and action names
        if (isset($config->default->controller)) {
            $this->controllerName = (string) $config->default->controller;
        }
        if (isset($config->default->action)) {
            $this->actionName = (string) $config->default->action;
        }

        // Get all routes
        foreach ($config as $routeName => $route) {
            foreach ($route as $parameterName => $value) {
                // If the value is an object, then convert it to an array
                if (is_object($value)) {
                    $array = [];
                    foreach ($value as $item) {
                        $array[] = $item;
                    }
                    $value = $array;
                }

                $this->_setRouteParameter($routeName, $parameterName, $value);
            }
        }
    }

    /**
     * Find the route based on the request
     *
     * @param   \Neolao\Site\Request    $request        Request instance
     * @return  \Neolao\Site\Request                    The updated request instance
     */
    public function handleRequest(Request $request)
    {
        $pathInfo = $request->getPathInfo();

        foreach ($this->_routes as $routeName => $route) {
            if (!isset($route['pattern'])) {
                continue;
            }

            $routePattern = $route['pattern'];
            $count = preg_match($routePattern, $pathInfo, $matches);
            if ($count > 0) {
                $request->routeName = $routeName;

                // Fill controller and action names
                $request->controllerName = $route['controller'];
                $request->actionName = $route['action'];

                // Fill parameter values
                array_shift($matches);
                $paramLength = count($matches);
                for ($paramIndex = 0; $paramIndex < $paramLength; $paramIndex++) {
                    $paramValue = $matches[$paramIndex];
                    if (isset($route['map']) && isset($route['map'][$paramIndex])) {
                        $paramName = $route['map'][$paramIndex];
                        $request->parameters[$paramName] = $paramValue;
                    }
                }
                break;
            }
        }

        return $request;
    }

    /**
     * Reverse a route
     *
     * @param   string  $routeName          Route name
     * @param   array   $parameters         Parameters
     * @return  string                      Reverse path
     */
    public function reverse($routeName, $parameters = [])
    {
        $result = '';

        // Get the reverse url
        if (isset($this->_routes[$routeName])) {
            $route = $this->_routes[$routeName];

            // Match parameters with the reverse route
            $map = [];
            if (isset($route['map'])) {
                $paramLength = count($route['map']);
                for ($index=0; $index < $paramLength; $index++) {
                    $paramName = $route['map'][$index];
                    if (isset($parameters[$paramName])) {
                        $map[$index] = $parameters[$paramName];
                        unset($parameters[$paramName]);
                    } else {
                        $map[$index] = '';
                    }
                }
            }
            $result = vsprintf($route['reverse'], $map);

            // For the rest of parameters, add a query string
            $query = array_merge($parameters);
            if (count($query) > 0) {
                $result .= '?' . http_build_query($query);
            }
        }

        return $result;
    }

    /**
     * Set route parameter
     *
     * @param   string  $routeName      Route name
     * @param   string  $parameterName  Parameter name
     * @param   mixed   $value          Parameter value
     */
    protected function _setRouteParameter($routeName, $parameterName, $value)
    {
        if (!isset($this->_routes[$routeName])) {
            $this->_routes[$routeName] = [];
        }

        $this->_routes[$routeName][$parameterName] = $value;
    }

}

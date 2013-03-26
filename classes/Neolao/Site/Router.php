<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site;

use \Neolao\Site\Router\RouteRegexp;

/**
 * Router
 */
class Router
{
    /**
     * List of routes
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
        foreach ($config as $routeName => $routeParameters) {
            // Create a route instance
            $route = new RouteRegexp();
            $route->configure($routeParameters);

            // Add the route instance to the list
            $this->_routes[$routeName] = $route;
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
        // Find the first route that matches the request
        foreach ($this->_routes as $routeName => $route) {
            $result = $route->handleRequest($request);

            if ($result) {
                $request->routeName = $routeName;
                return true;
            }
        }

        return false;
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
            $result = $route->reverse($parameters);
        }

        return $result;
    }
}

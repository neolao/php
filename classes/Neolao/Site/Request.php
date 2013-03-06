<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site;


use \Neolao\Util\Path;

/**
 * HTTP Request
 */
class Request
{
    use \Neolao\Mixin\GetterSetter;

    /**
     * Controller name
     * 
     * @var string
     */
    public $controllerName;

    /**
     * Action name
     * 
     * @var string
     */
    public $actionName;

    /**
     * Route name
     * 
     * @var string
     */
    public $routeName;

    /**
     * Parameters
     *
     * @var array
     */
    public $parameters;
    
    /**
     * URL path information
     * 
     * @var string
     */
    protected $_pathInfo;

    /**
     * URL Routes
     *
     * @var array
     */
    protected $_routes;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Get the path info
        $this->_pathInfo = Path::getPathInfo();
        
        // Get parameters and sanitize them
        $this->_getParameters();

        // Default values
        $this->controllerName   = 'index';
        $this->actionName       = 'index';
        $this->_routes          = array();
    }

    /**
     * Get the path info
     * 
     * @return  string      The path
     */
    public function getPathInfo()
    {
        return $this->_pathInfo;
    }
    
    /**
     * Configure the routes rules
     * 
     * @param   stdClass   $config     Configuration
     */
    public function configureRoutes($config)
    {
        $this->_routes = array();

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
                    $array = array();
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
     * Find the route based on the path info
     */
    public function handleRoute()
    {
        foreach ($this->_routes as $routeName => $route) {
            if (!isset($route['pattern'])) {
                continue;
            }

            $routePattern = $route['pattern'];
            $count = preg_match($routePattern, $this->_pathInfo, $matches);
            if ($count > 0) {
                $this->routeName = $routeName;

                // Fill controller and action names
                $this->controllerName = $route['controller'];
                $this->actionName = $route['action'];

                // Fill parameter values
                array_shift($matches);
                $paramLength = count($matches);
                for ($paramIndex=0; $paramIndex < $paramLength; $paramIndex++) {
                    $paramValue = $matches[$paramIndex];
                    if (isset($route['map']) && isset($route['map'][$paramIndex])) {
                        $paramName = $route['map'][$paramIndex];
                        $this->parameters[$paramName] = $paramValue;
                    }
                }
                return;
            }
        }
    }

    /**
     * Reverse a route
     *
     * @param   string  $routeName          Route name
     * @param   array   $parameters         Parameters
     * @return  string                      Reverse path
     */
    public function reverseRoute($routeName, $parameters = [])
    {
        $result = '';
        
        // Get the reverse url
        if (isset($this->_routes[$routeName])) {
            $route = $this->_routes[$routeName];
            
            // Match parameters with the reverse route
            $map = array();
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
                $result .= '?'.http_build_query($query);
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
            $this->_routes[$routeName] = array();
        }

        $this->_routes[$routeName][$parameterName] = $value;
    }
    
    /**
     * Get the paremeters sent by the client
     */
    protected function _getParameters()
    {
        $parameters = array();
        
        // Get the parameters from $_GET and $_POST
        if (get_magic_quotes_gpc()) {
            $_GET = array_map('stripslashes', $_GET);
            $_POST = array_map('stripslashes', $_POST);
            $_COOKIE = array_map('stripslashes', $_COOKIE);
        }
        $parameters = array_merge($parameters, $_GET, $_POST);
        
        
        $this->parameters = $parameters;
    }
}

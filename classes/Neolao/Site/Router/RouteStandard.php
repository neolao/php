<?php
namespace Neolao\Site\Router;

use \Neolao\Site\Request;

/**
 * Route with a standard pattern
 *
 * Example of a configuration:
 * <pre>
 * $config = [
 *      'pattern'       => '/account/:name/order/:orderId/pdf',
 *      'controller'    => 'foo',
 *      'action'        => 'bar'
 * ];
 * </pre>
 */
class RouteStandard extends RouteAbstract implements RouteInterface
{
    /**
     * Variable count
     *
     * @var int
     */
    protected $_variableCount;

    /**
     * Variable list
     *
     * @var array
     */
    protected $_variables;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_variableCount   = 0;
        $this->_variables       = [];
    }

    /**
     * Configure the route
     *
     * @param   array   $parameters         Parameters
     */
    public function configure(array $parameters)
    {
        parent::configure($parameters);

        // Get the variables
        $routePattern           = $this->_pattern;
        $variableCount          = preg_match_all('/:([a-zA-Z]+)/', $routePattern, $variables);
        if ($variableCount > 0) {
            $this->_variableCount   = $variableCount;
            $this->_variables       = $variables[1];
        } else {
            $this->_variableCount   = 0;
            $this->_variables       = [];
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

        // If there is no variable, then compare the pattern and the path info
        if ($this->_variableCount === 0) {
            if ($routePattern === $pathInfo) {
                $this->_updateRequest($request);
                return true;
            } else {
                return false;
            }
        }

        // Build the pattern
        $patternSplitted = preg_split('/:[a-zA-Z]+/', $routePattern);
        $pattern = '@^' . implode('([^/]+)', $patternSplitted) . '$@';

        // Check if the pattern matches the path info
        $count = preg_match($pattern, $pathInfo, $parameters);
        if ($count > 0) {
            $this->_updateRequest($request);

            // Update the request parameters
            array_shift($parameters);
            $parameterCount = count($parameters);
            for ($index = 0; $index < $parameterCount; $index++) {
                $value = $parameters[$index];
                $name = $this->_variables[$index];
                $request->parameters[$name] = $value;
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

        // Build the reverse
        $result = $this->_pattern;
        foreach ($parameters as $name => $value) {
            $key = ':' . $name;
            if (strpos($result, $key) >= 0) {
                $result = str_replace($key, $value, $result);
                unset($parameters[$name]);
            }
        }

        // For the rest of parameters, add a query string
        $query = array_merge($parameters);
        if (count($query) > 0) {
            $result .= '?' . http_build_query($query);
        }

        return $result;
    }

    /**
     * Update the request instance with the information of the route
     *
     * @param   \Neolao\Site\Request    $request    Request instance
     */
    protected function _updateRequest(Request $request)
    {
        // Fill controller and action names
        $request->controllerName = $this->_controller;
        $request->actionName = $this->_action;
    }
}

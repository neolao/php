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

    // Constants of the method
    const METHOD_GET        = 'GET';
    const METHOD_POST       = 'POST';
    const METHOD_PUT        = 'PUT';
    const METHOD_DELETE     = 'DELETE';
    const METHOD_OPTIONS    = 'OPTIONS';

    /**
     * Request method
     *
     * @var string
     */
    public $method;

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
    public $pathInfo;



    /**
     * Constructor
     */
    public function __construct()
    {
        // Request method
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

        // Get the path info
        $this->pathInfo = Path::getPathInfo();

        // Get parameters and sanitize them
        $this->_getParameters();

        // Default values
        $this->controllerName   = 'index';
        $this->actionName       = 'index';
    }

    /**
     * Get the paremeters sent by the client
     */
    protected function _getParameters()
    {
        $parameters = [];

        // Get the parameters from $_GET and $_POST
        if (get_magic_quotes_gpc()) {
            $_GET       = array_map('stripslashes', $_GET);
            $_POST      = array_map('stripslashes', $_POST);
            $_COOKIE    = array_map('stripslashes', $_COOKIE);
        }
        $parameters = array_merge($parameters, $_GET, $_POST);

        $this->parameters = $parameters;
    }
}

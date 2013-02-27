<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site;


use \Neolao\Site;
use \Neolao\Site\Request;
use \Neolao\Site\Helper\ControllerInterface;

/**
 * Abstract controller
 * All controllers are based on this class.
 * It provides core methods and helpers for fast implementation.
 */
abstract class Controller
{
    use \Neolao\Mixin\GetterSetter;

    /**
     * Site instance
     * 
     * @var Neo_Site
     */
    protected $_site;

    /**
     * Helper list
     *
     * @var array
     */
    protected $_helpers;
    
    
    
    /**
     * Constructor
     * 
     * @param   \Neolao\Site        $site       Site instance
     */
    public function __construct(Site $site)
    {
        $this->_helpers = array();
        $this->_site = $site;
    }

    /**
     * Register a helper instance
     *
     * @param   string                                      $key        Helper key in this controller
     * @param   \Neolao\Site\Helper\ControllerInterface     $helper     Helper instance
     */
    public function registerHelper($key, ControllerInterface $helper)
    {
        $helper->setController($this);
        $this->_helpers[$key] = $helper;
    }

    /**
     * Register a helper class name
     *
     * @param   string      $key            Helper key in this controller
     * @param   string      $helperClass    Helper class name
     * @param   array       $parameters     Parameters for the instance
     */
    public function registerHelperClass($key, $helperClass, $parameters = array())
    {
        $this->_helpers[$key] = array(
            'className'     => $helperClass,
            'parameters'    => $parameters
        );
    }

    /**
     * Magic method for functions
     *
     * @param   string      $name           Function name
     * @param   array       $arguments      Arguments
     * @return  mixed                       The result
     */
    public function __call($name, $arguments)
    {
        // Call a helper
        if (isset($this->_helpers[$name])) {
            $helper = $this->_helpers[$name];

            // Check if the helper needs to create an instance
            if ($helper instanceof ControllerInterface === false) {
                $helperClassName    = $helper['className'];
                $helperParameters   = $helper['parameters'];
                $helper             = new $helperClassName();
                if ($helper instanceof ControllerInterface) {
                    $helper->setController($this);
                    foreach ($helperParameters as $helperParameterName => $helperParameterValue) {
                        $helper->$helperParameterName = $helperParameterValue;
                    }
                    $this->_helpers[$name] = $helper;
                }
            }

            // Call the main method
            return call_user_func_array(
                array($helper, 'main'),
                $arguments
            );
        }
    }
    
    /**
     * Site instance
     * 
     * @var Site
     */
    public function get_site()
    {
        return $this->_site;
    }
    
    /**
     * View instance
     * 
     * @var Core_View
     */
    public function get_view()
    {
        return $this->site->view;
    }
    
    /**
     * Request instance
     * 
     * @var Cpre_Request
     */
    public function get_request()
    {
        return $this->site->request;
    }

    /**
     * Dispatch request
     * 
     * @param   \Neolao\Site\Request    $request    HTTP Request
     */
    public function dispatch(Request $request)
    {
        $actionName = $request->actionName;
        
        // Call shortcut action
        $actionMethodName = $actionName.'Action';
        if (!method_exists($this, $actionMethodName)) {
            throw new \Exception($actionMethodName.' is undefined in '.get_class($this));
        }
        $this->$actionMethodName();
    }
    
    /**
     * Forward to another controller
     * 
     * @param   string  $controllerName     The new controller name
     * @param   string  $actionName         The new action
     */
    public function forward($controllerName, $actionName = 'index')
    {
        $this->site->display($controllerName, $actionName);
    }
    
    /**
     * Redirect to a page
     *
     * @param   string  $routeName          Route name
     * @param   array   $parameters         Parameters
     */
    public function redirect($routeName, $parameters = array())
    {
        $this->site->redirect($routeName, $parameters);
    }
    
    /**
     * Render the view
     * 
     * @param   string  $viewName       View name
     */
    public function render($viewName)
    {
        $this->site->render($viewName);
    }
}

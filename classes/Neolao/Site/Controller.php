<?php
namespace Neolao\Site;

use \Neolao\Site;
use \Neolao\Site\Request;
use \Neolao\Site\Helper\ControllerInterface;
use \Neolao\Site\Controller\Helpers;

/**
 * Abstract controller
 *
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
     * @var \Neolao\Site\Controller\Helpers
     */
    protected $_helpers;



    /**
     * Constructor
     * 
     * @param   \Neolao\Site        $site       Site instance
     */
    public function __construct(Site $site)
    {
        $this->_site = $site;
        $this->_helpers = new Helpers($this);
    }

    /**
     * Register a helper instance
     *
     * @param   string                                      $key        Helper key in this controller
     * @param   \Neolao\Site\Helper\ControllerInterface     $helper     Helper instance
     */
    public function registerHelper($key, ControllerInterface $helper)
    {
        $this->_helpers->registerHelper($key, $helper);
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
        $this->_helpers->registerHelperClass($key, $helperClass, $parameters);
    }

    /**
     * Site instance
     * 
     * @var \Neolao\Site
     */
    public function get_site()
    {
        return $this->_site;
    }

    /**
     * View instance
     * 
     * @var \Neolao\Site\View
     */
    public function get_view()
    {
        return $this->site->view;
    }

    /**
     * Request instance
     * 
     * @var \Neolao\Site\Request
     */
    public function get_request()
    {
        return $this->site->request;
    }

    /**
     * Helpers container
     *
     * @var \Neolao\Site\Controller\Helpers
     */
    public function get_helpers()
    {
        return $this->_helpers;
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
            throw new \Exception($actionMethodName . ' is undefined in ' . get_class($this));
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
    public function redirect($routeName, $parameters = [])
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

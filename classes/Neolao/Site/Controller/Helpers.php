<?php
namespace Neolao\Site\Controller;

use \Neolao\Site\Controller;
use \Neolao\Site\Helper\ControllerInterface;

/**
 * Helpers container of a controller
 */
class Helpers
{
    /**
     * Helper list
     *
     * @var array
     */
    protected $_list;

    /**
     * Controller instance
     *
     * @var \Neolao\Site\Controller
     */
    protected $_controller;

    /**
     * Constructor
     *
     * @param   \Neolao\Site\Controller     $controller     Controller instance
     */
    public function __construct(Controller $controller)
    {
        $this->_controller = $controller;
        $this->_list = [];
    }

    /**
     * Register a helper instance
     *
     * @param   string                                      $key        Helper key in this controller
     * @param   \Neolao\Site\Helper\ControllerInterface     $helper     Helper instance
     */
    public function registerHelper($key, ControllerInterface $helper)
    {
        $helper->setController($this->_controller);
        $this->_list[$key] = $helper;
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
        $this->_list[$key] = array(
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
        if (isset($this->_list[$name])) {
            $helper = $this->_list[$name];

            // Check if the helper needs to create an instance
            if ($helper instanceof ControllerInterface === false) {
                $helperClassName    = $helper['className'];
                $helperParameters   = $helper['parameters'];
                $helper             = new $helperClassName();
                if ($helper instanceof ControllerInterface) {
                    $helper->setController($this->_controller);
                    foreach ($helperParameters as $helperParameterName => $helperParameterValue) {
                        $helper->$helperParameterName = $helperParameterValue;
                    }
                    $this->_list[$name] = $helper;
                }
            }

            // Call the main method
            return call_user_func_array(
                array($helper, 'main'),
                $arguments
            );
        }
    }
}

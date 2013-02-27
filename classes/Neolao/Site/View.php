<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site;


use \Neolao\Site\Helper\ViewInterface;

/**
 * A view
 */
class View
{
    /**
     * Site instance
     *
     * @var \Neolao\Site
     */
    public $site;

    /**
     * Directory path of the templates
     *
     * @var string
     */
    protected $_directoryPath;

    /**
     * Helper list
     *
     * @var array
     */
    protected $_helpers;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_helpers = array();
    }

    /**
     * Set the directory path of the templates
     *
     * @param   string  $directoryPath  Directory path
     */
    public function setDirectory($directoryPath)
    {
        $this->_directoryPath = $directoryPath;
    }
    
    /**
     * Render a view name
     * 
     * @param   string  $viewName       View name to render
     * @return  string                  The rendered view
     */
    public function render($viewName)
    {
        $templatePath = $this->_directoryPath.'/'.$viewName;
        if (!is_file($templatePath)) {
            throw new \Exception('Template file not found: '.$templatePath);
        }

        // Render
        $result = file_get_contents($templatePath);
        return $result;
    }

    /**
     * Register a helper instance
     *
     * @param   string                              $key        Helper key in this view
     * @param   \Neolao\Site\Helper\ViewInterface   $helper     Helper instance
     */
    public function registerHelper($key, ViewInterface $helper)
    {
        $this->_helpers[$key] = $helper;

        // Create the function on the view
        $view = $this;
        $this->$key = function($argument) use ($view, $helper) {
            return $helper->main($argument);
        };
    }

    /**
     * Register a helper class
     *
     * @param   string                      $key            Helper key in this view
     * @param   string                      $helperClass    Helper class name
     * @param   array                       $parameters     Parameters for the instance
     */
    public function registerHelperClass($key, $helperClass, $parameters = array())
    {
        $this->_helpers[$key] = array(
            'className'     => $helperClass,
            'parameters'    => $parameters
        );

        // Create the function on the view
        // Instanciate the helper only if necessary
        $view = $this;
        $this->$key = function($argument) use ($view, $key) {
            return $view->$key($argument);
        };
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
            if ($helper instanceof ViewInterface === false) {
                $helperClassName    = $helper['className'];
                $helperParameters   = $helper['parameters'];
                $helper             = new $helperClassName();
                if ($helper instanceof ViewInterface) {
                    // Set parameters
                    foreach ($helperParameters as $helperParameterName => $helperParameterValue) {
                        $helper->$helperParameterName = $helperParameterValue;
                    }

                    // Set the view
                    $helper->setView($this);

                    // Save the instance
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
}

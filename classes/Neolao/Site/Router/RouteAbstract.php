<?php
/**
 * @package Neolao\Site
 */
namespace Neolao\Site\Router;

/**
 * Common implementation of a route interface
 */
abstract class RouteAbstract
{
    /**
     * Pattern
     *
     * @var string
     */
    protected $_pattern;

    /**
     * Controller name
     *
     * @var string
     */
    protected $_controller;

    /**
     * Action name
     *
     * @var string
     */
    protected $_action;

    /**
     * Reverse
     *
     * @var string
     */
    protected $_reverse;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_pattern     = null;
        $this->_controller  = 'index';
        $this->_action      = 'index';
        $this->_reverse     = '';
    }

    /**
     * Configure the route
     *
     * @param   array   $parameters         Parameters
     */
    public function configure(array $parameters)
    {
        if (isset($parameters['pattern'])) {
            $this->_pattern = $parameters['pattern'];
        }
        if (isset($parameters['controller'])) {
            $this->_controller = $parameters['controller'];
        }
        if (isset($parameters['action'])) {
            $this->_action = $parameters['action'];
        }
        if (isset($parameters['reverse'])) {
            $this->_reverse = $parameters['reverse'];
        }
    }


}

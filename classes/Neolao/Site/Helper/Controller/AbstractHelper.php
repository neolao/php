<?php
namespace Neolao\Site\Helper\Controller;

use \Neolao\Site\Controller;
use \Neolao\Site\Helper\ControllerInterface;

/**
 * Abstract class for a helper controller
 */
abstract class AbstractHelper implements ControllerInterface
{
    /**
     * Controller instance
     *
     * @var \Neolao\Site\Controller
     */
    protected $_controller;



    /**
     * Set the controller
     *
     * @param   \Neolao\Site\Controller     $controller     Controller instance
     */
    public function setController(Controller $controller)
    {
        $this->_controller = $controller;
    }

    /**
     * Get the controller
     *
     * @return  \Neolao\Site\Controller                     Controller instance
     */
    public function getController()
    {
        return $this->_controller;
    }
}

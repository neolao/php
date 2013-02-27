<?php
/**
 * @package Neolao\Site\Helper\View
 */
namespace Neolao\Site\Helper\View;


use \Neolao\Site\View;
use \Neolao\Site\Helper\ViewInterface;

/**
 * Abstract class for a view helper
 */
abstract class AbstractHelper implements ViewInterface
{
    /**
     * View instance
     *
     * @var \Neolao\Site\View
     */
    protected $_view;

    /**
     * The main function
     *
     * @param   mixed   $argument   The argument
     */
    public function main($argument)
    {
    }

    /**
     * Set the view
     *
     * @param   \Neolao\Site\View       $view       View instance
     */
    public function setView(View $view)
    {
        $this->_view = $view;
    }

    /**
     * Get the view
     *
     * @return  \Neolao\Site\View                   View instance
     */
    public function getView()
    {
        return $this->_view;
    }

}

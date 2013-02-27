<?php
/**
 * @package Neolao\Site\Helper
 */
namespace Neolao\Site\Helper;


use \Neolao\Site\View;

/**
 * Interface for a view helper
 *
 * The helper must contain a method named "main"
 */
interface ViewInterface
{
    /**
     * The main function
     *
     * @param   mixed   $argument   The argument
     */
    function main($argument);

    /**
     * Set the view
     *
     * @param   \Neolao\Site\View       $view       View instance
     */
    function setView(View $view);

    /**
     * Get the view
     *
     * @return  \Neolao\Site\View                   View instance
     */
    function getView();

}

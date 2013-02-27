<?php
/**
 * @package Neolao\Site\Helper\View
 */
namespace Neolao\Site\Helper\View;


use \Neolao\Site\Helper\View\AbstractHelper;

/**
 * Sample
 */
class ExampleHelper extends AbstractHelper
{
    /**
     * The main function
     *
     * @param   mixed   $argument   The only one argument
     */
    public function main($argument)
    {
        return 'example of a view helper: '.$argument;
    }
}

<?php
namespace Neolao\Behavior;

/**
 * Objects that implement this interface are singleton
 */
interface Singleton
{
    /**
     * Get the singleton instance
     *
     * @return   __CLASS__      The singleton instance
     */
    function getInstance();
}

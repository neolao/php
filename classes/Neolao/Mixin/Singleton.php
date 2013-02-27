<?php
/**
 * @package Neolao\Mixin
 */
namespace Neolao\Mixin;

/**
 * Create a singleton access
 */
trait Singleton
{
    /**
     * Singleton instance
     *
     * @var __CLASS__
     */
    protected static $_instance;

    /**
     * Constructor
     */
    protected function __construct()
    {
    }


    /**
     * Get the singleton instance
     *
     * @return   __CLASS__      The singleton instance
     */
    final public static function getInstance()
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * Wake up
     */
    final private function __wakeup()
    {
    }

    /**
     * Clone
     */
    final private function __clone()
    {
    }
}

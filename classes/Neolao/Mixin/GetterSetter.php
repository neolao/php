<?php
/**
 * @package Neolao\Mixin
 */
namespace Neolao\Mixin;

/**
 * Create a getter method with the pattern "get_*".
 * Create a setter method with the pattern "set_*".
 *
 * Example:
 * class Foo
 * {
 *      use \Neolao\Mixin\GetterSetter;
 *
 *      private $_bar;
 *
 *      public function get_bar()
 *      {
 *          return $this->_bar;
 *      }
 *      public function set_bar($value)
 *      {
 *          $this->_bar = $value;
 *      }
 * }
 * $foo = new Foo();
 * $foo->bar = 42;
 * echo $foo->bar; // 42
 */
trait GetterSetter
{
    /**
     * Getter magic method
     *
     * @param   string  $name   Property name
     * @return  mixed           Property value
     */
    public function __get($name)
    {
        if (method_exists($this, "get_$name")) {
            return $this->{"get_$name"}();
        } else if (method_exists($this, "set_$name")) {
            throw new \Exception("Writeonly property $name");
        } else {
            //throw new \Exception("Undefined property $name");
        }
    }

    /**
     * Setter magic method
     *
     * @param   string  $name   Property name
     * @param   mixed   $value  New property value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, "set_$name")) {
            $this->{"set_$name"}($value);
        } else if (method_exists($this, "get_$name")) {
            throw new \Exception("Readonly property $name");
        } else {
            //throw new \Exception("Undefined property $name");
        }
    }

}

<?php
namespace Neolao\Util;

/**
 * Class utility to work with JSON
 */
class Json
{
    /**
     * Remove comments
     *
     * @param   string      $json       JSON string
     * @return  string                  JSON string without comments
     */
    public static function removeComments($json)
    {
        $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $json);
        return $json;
    }
}

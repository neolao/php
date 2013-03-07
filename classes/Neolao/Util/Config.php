<?php
/**
 * @package Neolao\Util
 */
namespace Neolao\Util;

/**
 * Configuration with profiles
 *
 * Example of json file:
 * {
 *      "profile"   : "production",
 *      "myName"    : "unknown",
 *
 *      "profiles"  : {
 *          "production" : {
 *              "myName" : "A"
 *          },
 *
 *          "development" : {
 *              "myName" : "B"
 *          }
 *      }
 * }
 *
 * PHP:
 * $config = new \Neolao\Util\Config();
 * $config->parseJson($jsonContent);
 * echo $config->myName; // A
 *
 */
class Config
{
    /**
     * Parse a json
     *
     * @param   string      $json       Json string
     */
    public function parseJson($json)
    {
        // Decode the json string if necessary
        if (is_string($json)) {
            $json = json_decode($json);
        }

        // Import properties except the profiles
        foreach ($json as $propertyName => $propertyValue) {
            if ($propertyName === 'profiles') {
                continue;
            }
            $this->$propertyName = $propertyValue;
        }

        // Get the selected profile
        $profile = 'default';
        if (isset($json->profile)) {
            $profile = (string) $json->profile;
        }

        // Override the properties with the selected profile
        if (isset($json->profiles) && isset($json->profiles->$profile)) {
            $newProperties = $json->profiles->$profile;

            foreach ($newProperties as $propertyName => $propertyValue) {
                $this->$propertyName = $propertyValue;
            }
        }
    }
}

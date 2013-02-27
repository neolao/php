<?php
/**
 * @package Neolao\Util
 */
namespace Neolao\Util;

/**
 * Class utility for paths and urls
 */
class Path
{
    /**
     * The base path of the page
     * 
     * @var string
     */
    private static $_basePath;
    
    /**
     * The base url of the page
     * 
     * @var string
     */
    private static $_baseUrl;
    
    
    
    /**
     * Get the base path of the page
     * 
     * @return  string      The path
     */
    public static function getBasePath()
    {
        if (!isset(self::$_basePath)) {
            $scriptFilename = $_SERVER['SCRIPT_FILENAME'];
            self::$_basePath = dirname($scriptFilename);
            if (self::$_basePath === '/') {
                self::$_basePath = '';
            }
        }
        return self::$_basePath;
    }
    
    /**
     * Get the base url of the page
     * 
     * @return  string      The url
     */
    public static function getBaseUrl()
    {
        if (!isset(self::$_baseUrl)) {
            $phpSelf = $_SERVER['SCRIPT_NAME'];
            self::$_baseUrl = dirname($phpSelf);
            if (self::$_baseUrl === '/') {
                self::$_baseUrl = '';
            }
        }
        return self::$_baseUrl;
    }
    
    /**
     * Get the path info of the page
     * 
     * @return  string      The url
     */
    public static function getPathInfo()
    {
        if (!isset($_SERVER['PATH_INFO'])) {
            return '/';
        }
        $pathInfo = $_SERVER['PATH_INFO'];
        if (empty($pathInfo)) {
            $pathInfo = '/';
        }
        return $pathInfo;
    }
}

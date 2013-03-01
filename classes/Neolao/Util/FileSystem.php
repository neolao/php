<?php
/**
 * @package Neolao\Util
 */
namespace Neolao\Util;

/**
 * Class utility to work with the file system
 */
class FileSYstem
{
    /**
     * Copy directory
     *
     * @param   string      $path       Directory path
     * @param   string      $newPath    New directory path
     */
    public static function copyDirectory($path, $newPath)
    {
        // If the original path is not a directory, then do nothing
        if (!is_dir($path)) {
            return;
        }
        $path = realpath($path);

        // Create the new directory if necessary
        if (!is_dir($newPath)) {
            mkdir($newPath, 0777 - umask(), true);
        }
        $newPath = realpath($newPath);

        // Copy files
        $ignore = ['.', '..'];
        $directory = opendir($path);
        while (false !== ($file = readdir($directory))) {
            if (in_array($file, $ignore)) {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                self::copyDirectory($path . DIRECTORY_SEPARATOR . $file, $newPath . DIRECTORY_SEPARATOR . $file);
            } else {
                copy($path . DIRECTORY_SEPARATOR . $file, $newPath . DIRECTORY_SEPARATOR . $file);
            }
        }
        closedir($directory);
    }
}

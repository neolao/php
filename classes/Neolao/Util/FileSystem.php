<?php
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

    /**
     * Recurvise glob (see glob function)
     *
     * @param   string  $pattern    The file pattern
     * @param   string  $path       The base path
     * @return  array               File list matching the pattern
     */
    public static function rglob($pattern, $path)
    {
        $paths = glob($path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
        $files = glob($path . $pattern, GLOB_MARK | GLOB_BRACE);
        foreach ($paths as $path) {
            $files = array_merge($files, self::rglob($pattern, $path));
        }
        return $files;
    }
}

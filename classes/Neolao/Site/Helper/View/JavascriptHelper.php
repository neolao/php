<?php
namespace Neolao\Site\Helper\View;

use \Neolao\Logger;
use \Neolao\Util\FileSystem;
use \Neolao\Site\Helper\View\AbstractHelper;
use \JShrink\Minifier;

/**
 * Helper to get a file URL from the javascript relative path
 *
 *
 * Example 1: Get the url of the original file
 * <pre>
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * echo $helper->getFileUrl('general.js');                          // http://site.tld/style/javascripts/general.js
 * echo $helper->getFileUrl('lib/foo/bar.js');                      // http://site.tld/style/javascripts/lib/foo/bar.js
 * </pre>
 *
 * Example 2: Get the url of the generated file
 * <pre>
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $helper->generated   = true;
 * echo $helper->getFileUrl('general.js');                          // http://site.tld/style/generated-js/general.a75ca72342d875fb6d50b03e4a6dd5a4.js
 *                                                                  // based on /path/to/style/generated-js/general.version.txt
 * echo $helper->getFileUrl('lib/foo/bar.js');                      // http://site.tld/style/generated-js/lib/foo/bar.a75ca72342d875fb6d50b03e4a6dd5a4.js
 *                                                                  // based on /path/to/style/generated-js/lib/foo/bar.version.txt
 * </pre>
 */
class JavascriptHelper extends AbstractHelper
{
    /**
     * The base path
     *
     * @var string
     */
    public $basePath = '';

    /**
     * The base URL
     *
     * @var string
     */
    public $baseUrl = '';

    /**
     * Indicates that the javascript is generated
     *
     * @var bool
     */
    public $generated = false;

    /**
     * Get the file URL of the specified file name
     * (access point of the view helper)
     *
     * @param   string  $fileName   The file name
     * @return  string              The file URL
     */
    public function main($fileName)
    {
        return $this->getFileUrl($fileName);
    }

    /**
     * Get the file URL of the specified file name
     *
     * @param   string  $fileName   The file name
     * @return  string              The file URL
     */
    public function getFileUrl($fileName)
    {
        // By default, use the original file
        $filePath   = $this->basePath . '/javascripts/' . $fileName;
        $fileUrl    = $this->baseUrl . '/javascripts/' . $fileName;

        // Check if the file is really in the directory "javascripts"
        $filePath   = realpath($filePath);
        if (strpos($filePath, $this->basePath . '/javascripts/') !== 0) {
            return '';
        }

        // If specified, use the generated file
        if ($this->generated) {
            $generatedDirectory = $this->basePath . '/generated-js';
            $fileSubDirectory   = pathinfo($fileName, PATHINFO_DIRNAME);
            $realFileName       = pathinfo($fileName, PATHINFO_FILENAME);

            // Get the version
            $versionPath = $generatedDirectory . '/';
            if (!empty($fileSubDirectory)) {
                $versionPath .= $fileSubDirectory . '/';
            }
            $versionPath .= $realFileName . '.version.txt';
            if (!is_readable($versionPath)) {
                return $fileUrl;
            }
            $version = file_get_contents($versionPath);
            $version = trim($version);

            // Generated URL
            $fileUrl = $this->baseUrl . '/generated-js/';
            if (!empty($fileSubDirectory)) {
                $fileUrl .= $fileSubDirectory . '/';
            }
            $fileUrl .= $realFileName . '.' . $version . '.js';
        }

        return $fileUrl;
    }

    /**
     * Generate a snapshot of the javascripts
     */
    public function generate()
    {
        $javascriptsDirectory   = realpath($this->basePath) . '/javascripts';
        $generatedDirectory     = realpath($this->basePath) . '/generated-js';

        // Get JS files
        $filePaths = FileSystem::rglob('*.js', $javascriptsDirectory . '/');

        // Minify the original files
        // They must be readable
        foreach ($filePaths as $filePath) {
            if (!is_readable($filePath)) {
                $logger = Logger::getInstance();
                $logger->warning('The file ' . $filePath . ' is not readable');
                return;
            }

            // Get the file name and the directory paths
            $fileName               = pathinfo($filePath, PATHINFO_FILENAME);
            $fileDirectory          = pathinfo($filePath, PATHINFO_DIRNAME);
            $fileSubDirectory       = substr($fileDirectory, strlen($javascriptsDirectory));
            $fileGeneratedDirectory = $generatedDirectory . $fileSubDirectory;

            // Create the generated directory if necessary
            if (!is_dir($fileGeneratedDirectory)) {
                mkdir($fileGeneratedDirectory, 0777 - umask(), true);
            }

            // Get the file content
            $fileContent = file_get_contents($filePath);

            // Get the checksum
            $checksum = md5($fileContent);

            // Minify
            $generatedFilePath = $fileGeneratedDirectory . '/' . $fileName . '.' . $checksum . '.js';
            $minified = Minifier::minify($fileContent);
            file_put_contents($generatedFilePath, $minified);


            // Save the version
            $versionPath = $fileGeneratedDirectory . '/' . $fileName . '.version.txt';
            if (file_exists($versionPath) && !is_writable($versionPath)) {
                $logger = Logger::getInstance();
                $logger->warning('The file ' . $versionPath . ' is not writable');
                return;
            }
            file_put_contents($versionPath, $checksum);

        }
    }
}


<?php
/**
 * @package Neolao\Site\Helper\View
 */
namespace Neolao\Site\Helper\View;


use \Neolao\Logger;
use \Neolao\Site\Helper\View\AbstractHelper;

/**
 * Helper to get a file URL from the style
 *
 *
 * Example 1: Get the url of the file screen.css
 *
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/stylesheets/screen.css
 *
 *
 * Example 2: Compile with SASS and get the url of the file screen.css
 *
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $helper->sass        = true;                                     // Compile /path/to/style/sass/screen.scss
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/stylesheets/screen.css
 *
 *
 * Example 3: Get the url if the generated file screen.css
 *
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $helper->generated   = true;                                     // Get the version from /path/to/style/generated-styles/version.txt
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/generated-styles/a75ca72342d875fb6d50b03e4a6dd5a4/stylesheets/screen.css
 */
class StylesheetHelper extends AbstractHelper
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
     * Indicates that the stylesheet is pre-compiled with SASS (everytime)
     *
     * @var bool
     */
    public $sass = false;

    /**
     * Indicates that the stylesheet is generated
     *
     * @var bool
     */
    public $generated = false;

    /**
     * Get the file URL of the specified file name
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
        $filePath   = $this->basePath . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR . $fileName;
        $fileUrl    = $this->baseUrl . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR . $fileName;

        // If specified, pre-compile the original file
        if ($this->sass) {
            $sassFilePath = $this->basePath . DIRECTORY_SEPARATOR . 'sass' . DIRECTORY_SEPARATOR . pathinfo($fileName, PATHINFO_FILENAME) . '.scss';
            $this->_sassCompile($sassFilePath, $filePath);
        }

        // If specified, use the generated file
        if ($this->generated) {
            $generatedDirectory = $this->basePath . DIRECTORY_SEPARATOR . 'generated-styles';

            // Get the version
            $versionPath = $generatedDirectory . DIRECTORY_SEPARATOR . 'version.txt';
            if (!is_readable($versionPath)) {
                return $fileUrl;
            }
            $version = file_get_contents($versionPath);
            $version = trim($version);

            // Generated URL
            $fileUrl = $this->baseUrl . DIRECTORY_SEPARATOR . 'generated-styles' . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR . $fileName;
        }

        return $fileUrl;
    }

    /**
     * Compile the file with SASS
     *
     * @param   string  $sassFilePath   The SASS file path
     * @param   string  $filePath       The compiled file path
     */
    protected function _sassCompile($sassFilePath, $filePath)
    {
        // The compiled file path must be writable
        if (!is_writable($filePath)) {
            $logger = Logger::getInstance();
            $logger->warning('The file ' . $filePath . ' is not writable');
            return;
        }

        // The SASS file must be readable
        if (!is_readable($sassFilePath)) {
            $logger = Logger::getInstance();
            $logger->warning('The file ' . $sassFilePath . ' is not readable');
            return;
        }

        // Compile
        $options = [
            'syntax'    => 'scss'
        ];
        try {
            $parser = new \SassParser($options);
            $content = $parser->toCss($sassFilePath);
            file_put_contents($filePath, $content);
        } catch (\Exception $error) {
            $logger = Logger::getInstance();
            $logger->warning('Unable to compile ' . $filePath . ': ' . $error->getMessage());
        }
    }
}


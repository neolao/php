<?php
/**
 * @package Neolao\Site\Helper\View
 */
namespace Neolao\Site\Helper\View;


use \Neolao\Site\Helper\View\AbstractHelper;

/**
 * Helper to get a file URL from the style
 *
 * Example:
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 *
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl; // http://site.tld/style/stylesheets/screen.css
 *
 * $helper->generated   = true;
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl; // http://site.tld/style/generated-style/a75ca72342d875fb6d50b03e4a6dd5a4/stylesheets/screen.css
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
        $fileUrl = $this->baseUrl . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR . $fileName;

        if ($this->generated) {
            // Use the generated file
            $generatedDirectory = $this->basePath . DIRECTORY_SEPARATOR . 'generated-styles';

            // Get the version
            $versionPath = $generatedDirectory . DIRECTORY_SEPARATOR . 'version.txt';
            if (!is_readable($versionPath)) {
                return $fileUrl;
            }
            $version = file_get_contents($versionPath);

            // Generated URL
            $fileUrl = $this->baseUrl . DIRECTORY_SEPARATOR . 'generated-styles' . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR . $fileName;
        }

        return $fileUrl;
    }
}

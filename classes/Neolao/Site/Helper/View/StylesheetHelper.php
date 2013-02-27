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
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl; // http://site.tld/style/stylesheets/screen.css
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
        $fileUrl = $this->baseUrl . '/stylesheets/' . $fileName;

        return $fileUrl;
    }
}

<?php
namespace Neolao\Site\Helper\View;

use \Neolao\Logger;
use \Neolao\Util\FileSystem;
use \Neolao\Site\Helper\View\AbstractHelper;

/**
 * Helper to get a file URL from the style
 *
 *
 * Example 1: Get the url of the file screen.css
 * <pre>
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/stylesheets/screen.css
 * </pre>
 *
 * Example 2: Compile with SASS and get the url of the file screen.css
 * <pre>
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $helper->sass        = true;                                     // Compile /path/to/style/sass/screen.scss
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/stylesheets/screen.css
 * </pre>
 *
 * Example 3: Get the url of the generated file screen.css
 * <pre>
 * $helper              = new Stylesheet();
 * $helper->basePath    = '/path/to/style';
 * $helper->baseUrl     = 'http://site.tld/style';
 * $helper->generated   = true;                                     // Get the version from /path/to/style/generated-styles/version.txt
 * $fileUrl             = $helper->getFileUrl('screen.css');
 * echo $fileUrl;                                                   // http://site.tld/style/generated-styles/a75ca72342d875fb6d50b03e4a6dd5a4/stylesheets/screen.css
 * </pre>
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
        $filePath   = $this->basePath . '/stylesheets/' . $fileName;
        $fileUrl    = $this->baseUrl . '/stylesheets/' . $fileName;

        // If specified, pre-compile the original file
        if (!$this->generated && $this->sass) {
            $sassFilePath = $this->basePath . '/sass/' . pathinfo($fileName, PATHINFO_FILENAME) . '.scss';
            $this->_sassCompile($sassFilePath, $filePath);
        }

        // If specified, use the generated file
        if ($this->generated) {
            $generatedDirectory = $this->basePath . '/generated-styles';

            // Get the version
            $versionPath = $generatedDirectory . '/version.txt';
            if (!is_readable($versionPath)) {
                return $fileUrl;
            }
            $version = file_get_contents($versionPath);
            $version = trim($version);

            // Generated URL
            $fileUrl = $this->baseUrl . '/generated-styles/' . $version . '/stylesheets/' . $fileName;
        }

        return $fileUrl;
    }

    /**
     * Generate a snapshot of the style
     */
    public function generate()
    {
        // Get CSS files
        $stylesheetsDirectory = $this->basePath . '/stylesheets';
        $filePaths = glob($stylesheetsDirectory . '/*.css');

        // If specified, pre-compile the original files
        if ($this->sass) {
            foreach ($filePaths as $filePath) {
                $sassFilePath = $this->basePath . '/sass/' . pathinfo($filePath, PATHINFO_FILENAME) . '.scss';
                $this->_sassCompile($sassFilePath, $filePath);
            }
        }

        // Unify the original files
        // They must be readable
        $unified = '';
        foreach ($filePaths as $filePath) {
            if (!is_readable($filePath)) {
                $logger = Logger::getInstance();
                $logger->warning('The file ' . $filePath . ' is not readable');
                return;
            }
            $fileContent = file_get_contents($filePath);
            $unified .= $fileContent;
        }

        // Get the checksum
        $checksum = md5($unified);

        // Copy the entire style into a new directory
        // If the directory already exists, then do nothing
        $snapshotDirectory = $this->basePath . '/generated-styles/' . $checksum;
        if (is_dir($snapshotDirectory)) {
            return;
        }
        mkdir($snapshotDirectory, 0777 - umask(), true);
        FileSystem::copyDirectory($this->basePath . '/stylesheets', $snapshotDirectory . '/stylesheets');
        FileSystem::copyDirectory($this->basePath . '/images', $snapshotDirectory . '/images');
        FileSystem::copyDirectory($this->basePath . '/fonts', $snapshotDirectory . '/fonts');

        // Minify
        $compressor = new \CSSmin();
        $newFilePaths = glob($snapshotDirectory . '/stylesheets/*.css');
        foreach ($newFilePaths as $newFilePath) {
            $newFileContent = file_get_contents($newFilePath);
            $minified = $compressor->run($newFileContent);
            file_put_contents($newFilePath, $minified);
        }

        // Save the version
        $versionPath = $this->basePath . '/generated-styles/version.txt';
        if (file_exists($versionPath) && !is_writable($versionPath)) {
            $logger = Logger::getInstance();
            $logger->warning('The file ' . $versionPath . ' is not writable');
            return;
        }
        file_put_contents($versionPath, $checksum);
    }

    /**
     * Compile the file with SASS
     *
     * @param   string  $sassFilePath   The SASS file path
     * @param   string  $filePath       The compiled file path
     */
    protected function _sassCompile($sassFilePath, $filePath)
    {
        // The compiled file must be writable
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
            //$parser = new \SassParser($options);
            //$content = $parser->toCss($sassFilePath);
            $parser = new \scssc();
            $parser->setImportPaths(dirname($sassFilePath) . '/');
            $source = file_get_contents($sassFilePath);
            $content = $parser->compile($source);
            file_put_contents($filePath, $content);
        } catch (\Exception $error) {
            $logger = Logger::getInstance();
            $logger->warning('Unable to compile ' . $filePath . ': ' . $error->getMessage());
        }
    }
}


<?php
// Check PHP version
if (version_compare(PHP_VERSION, '5.4.0') < 0) {
     die('You need PHP version 5.4.0 or above. Your version is '.PHP_VERSION.'.');
}

// PHP configuration
ini_set('html_errors', 'Off');

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';


use \Neolao\Util\Cli;
use \Neolao\Logger;
use \Neolao\Logger\StdoutListener;

// Get parameters
$arguments = $_SERVER['argv'];
array_shift($arguments);
if (empty($arguments)) {
    echo Cli::getColoredString('Style path is not defined', 'red'), "\n";
    exit;
}
$stylePath = array_shift($arguments);

// Check if the style path is a directory
if (!is_dir($stylePath)) {
    echo Cli::getColoredString('This path is not a directory', 'red'), "\n";
    exit;
}
$stylePath = realpath($stylePath);

// Error handler
function defaultErrorHandler($level, $message, $file, $line)
{
    throw new Exception("$message ($file:$line)");
}
set_error_handler('defaultErrorHandler');


// Initialize the logger
$logger = Logger::getInstance();
$fileListener = new StdoutListener();
$logger->addListener($fileListener);

// Generate the style
echo Cli::getColoredString('Generating ... ', 'yellow');
try {
    $stylesheetHelper           = new \Neolao\Site\Helper\View\StylesheetHelper();
    $stylesheetHelper->basePath = $stylePath;
    $stylesheetHelper->generate();
    $javascriptHelper           = new \Neolao\Site\Helper\View\JavascriptHelper();
    $javascriptHelper->basePath = $stylePath;
    $javascriptHelper->generate();
    echo Cli::getColoredString('OK', 'green'), "\n";
} catch (\Exception $error) {
    echo Cli::getColoredString('ERROR', 'red'), "\n";
    echo $error->getMessage(), "\n";
    echo $error->getTraceAsString(), "\n";
}

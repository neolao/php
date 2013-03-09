<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */
/**
 * @package Neolao
 */
namespace Neolao;


use \Neolao\Site\Controller;
use \Neolao\Site\View;
use \Neolao\Site\Helper\View\StylesheetHelper;
use \Neolao\Site\Helper\View\JavascriptHelper;
use \Neolao\Site;

/**
 * Site with helpers
 */
class SiteAdvanced extends Site
{
    /**
     * Theme path
     *
     * @var string
     */
    protected $_themePath;

    /**
     * Theme URL
     *
     * @var string
     */
    protected $_themeUrl;

    /**
     * Indicates that the theme is generated
     *
     * @var bool
     */
    protected $_themeGenerated;

    /**
     * Stylesheet helper for the view
     *
     * @var \Neolao\Site\Helper\View\StylesheetHelper
     */
    protected $_stylesheetHelper;

    /**
     * Javascript helper for the view
     *
     * @var \Neolao\Site\Helper\View\JavascriptHelper
     */
    protected $_javascriptHelper;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Default values
        $this->_themeGenerated = false;

        // Initialize the stylesheet helper for the view
        $this->_stylesheetHelper        = new StylesheetHelper();
        $this->_stylesheetHelper->sass  = true;

        // Initialize the javascript helper for the view
        $this->_javascriptHelper        = new JavascriptHelper();

        // Add the helpers
        $this->addViewHelper('stylesheetsPath', $this->_stylesheetHelper);
        $this->addViewHelper('javascriptsPath', $this->_javascriptHelper);
    }

    /**
     * Theme path
     *
     * @var string
     */
    protected function get_themePath()
    {
        return $this->_themePath;
    }
    protected function set_themePath($path)
    {
        $this->_themePath = $path;

        // Update the helpers
        $this->_stylesheetHelper->basePath = $this->_themePath;
        $this->_javascriptHelper->basePath = $this->_themePath;
    }

    /**
     * Theme URL
     *
     * @var string
     */
    protected function get_themeUrl()
    {
        return $this->_themeUrl;
    }
    protected function set_themeUrl($url)
    {
        $this->_themeUrl = $url;

        // Update the helpers
        $this->_stylesheetHelper->baseUrl = $this->_themeUrl;
        $this->_javascriptHelper->baseUrl = $this->_themeUrl;
    }

    /**
     * Indicates that the themeis generated
     *
     * @var bool
     */
    protected function get_themeGenerated()
    {
        return $this->_themeGenerated;
    }
    protected function set_themeGenerated($value)
    {
        $this->_themeGenerated = $value;

        // Update the helpers
        $this->_stylesheetHelper->generated = $this->_themeGenerated;
        $this->_javascriptHelper->generated = $this->_themeGenerated;
    }




    /**
     * Add controller helpers
     *
     * @param   \Neolao\Site\Controller     $controller     Controller instance
     */
    protected function _addControllerHelpers(Controller $controller)
    {
        parent::_addControllerHelpers($controller);

        $controller->registerHelperClass('link', '\\Neolao\\Site\\Helper\\Controller\\LinkHelper');
    }

    /**
     * Add view helpers
     * 
     * @param   \Neolao\Site\View           $view           View instance
     */
    protected function _addViewHelpers(View $view)
    {
        parent::_addViewHelpers($view);

        $view->registerHelperClass('link', '\\Neolao\\Site\\Helper\\View\\RouteReverseHelper');
    }

}

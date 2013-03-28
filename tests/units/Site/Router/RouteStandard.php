<?php
namespace Neolao\tests\units\Site\Router;

use \mageekguy\atoum;
use \Neolao\Site\Request;

/**
 * Test of \Neolao\Site\Router\RouteStandard
 */
class RouteStandard extends atoum\test
{
    /**
     * Route instance
     *
     * @var \Neolao\Site\Router\RouteStandard
     */
    public $route;

    /**
     * Executed before each test
     */
    public function beforeTestMethod()
    {
        $this->route = new \Neolao\Site\Router\RouteStandard();
    }

    /**
     * Method "handleRequest"
     *
     * Check the path "/"
     */
    public function testHandleRequest01()
    {
        // Initialize the request
        $request            = new Request();
        $request->pathInfo  = '/';

        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/'
        ];
        $route->configure($config);

        // Handle the request
        $result = $route->handleRequest($request);

        // Check the result
        $this->assert->boolean($result)->isTrue();
    }

    /**
     * Method "handleRequest"
     *
     * Check the path "/a"
     */
    public function testHandleRequest02()
    {
        // Initialize the requests
        $request                = new Request();
        $request->pathInfo      = '/a';
        $requestWrong           = new Request();
        $requestWrong->pathInfo = '/b';

        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/a',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/b'
        ];
        $route->configure($config);

        // Handle the requestq
        $result         = $route->handleRequest($request);
        $resultWrong    = $route->handleRequest($requestWrong);

        // Check the result
        $this->assert->boolean($result)->isTrue();
        $this->assert->boolean($resultWrong)->isFalse();
    }

    /**
     * Method "handleRequest"
     *
     * Check the path "/account/:name"
     */
    public function testHandleRequest03()
    {
        // Initialize the requests
        $request                = new Request();
        $request->pathInfo      = '/account/olivia';
        $requestWrong           = new Request();
        $requestWrong->pathInfo = '/account/olivia/facebook';

        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/account/:name',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/account/%s'
        ];
        $route->configure($config);

        // Handle the requestq
        $result         = $route->handleRequest($request);
        $resultWrong    = $route->handleRequest($requestWrong);

        // Check the result
        $this->assert->boolean($result)->isTrue();
        $this->assert->string($request->parameters['name'])->isEqualTo('olivia');
        $this->assert->boolean($resultWrong)->isFalse();
    }

    /**
     * Method "handleRequest"
     *
     * Check the path "/account/:name/:section"
     */
    public function testHandleRequest04()
    {
        // Initialize the requests
        $request                = new Request();
        $request->pathInfo      = '/account/olivia/facebook';
        $requestWrong           = new Request();
        $requestWrong->pathInfo = '/account/olivia';

        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/account/:name/:section',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/account/%s/%s'
        ];
        $route->configure($config);

        // Handle the requestq
        $result         = $route->handleRequest($request);
        $resultWrong    = $route->handleRequest($requestWrong);

        // Check the result
        $this->assert->boolean($result)->isTrue();
        $this->assert->string($request->parameters['name'])->isEqualTo('olivia');
        $this->assert->string($request->parameters['section'])->isEqualTo('facebook');
        $this->assert->boolean($resultWrong)->isFalse();
    }

    /**
     * Method "handleRequest"
     *
     * Check the path "/account/:name/order/:orderId/pdf"
     */
    public function testHandleRequest05()
    {
        // Initialize the requests
        $request                = new Request();
        $request->pathInfo      = '/account/olivia/order/42/pdf';
        $requestWrong           = new Request();
        $requestWrong->pathInfo = '/account/olivia/order/42';

        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/account/:name/order/:orderId/pdf',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/account/%s/order/%d/pdf'
        ];
        $route->configure($config);

        // Handle the requestq
        $result         = $route->handleRequest($request);
        $resultWrong    = $route->handleRequest($requestWrong);

        // Check the result
        $this->assert->boolean($result)->isTrue();
        $this->assert->string($request->parameters['name'])->isEqualTo('olivia');
        $this->assert->string($request->parameters['orderId'])->isEqualTo('42');
        $this->assert->boolean($resultWrong)->isFalse();
    }

    /**
     * Method "reverse"
     *
     * Check the path "/"
     */
    public function testReverse01()
    {
        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/'
        ];
        $route->configure($config);

        // Get the reverse
        $result = $route->reverse();

        // Check the result
        $this->assert->string($result)->isEqualTo('/');
    }

    /**
     * Method "reverse"
     *
     * Check the path "/account/:name"
     */
    public function testReverse02()
    {
        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/account/:name',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/account/%s'
        ];
        $route->configure($config);

        // Get the reverse
        $parameters = [
            'name'  => 'olivia'
        ];
        $result = $route->reverse($parameters);

        // Check the result
        $this->assert->string($result)->isEqualTo('/account/olivia');
    }

    /**
     * Method "reverse"
     *
     * Check the path "/account/:name/order/:orderId/pdf"
     */
    public function testReverse03()
    {
        // Initialize the route
        $route = $this->route;
        $config = [
            'pattern'       => '/account/:name/order/:orderId/pdf',
            'controller'    => 'foo',
            'action'        => 'bar',
            'reverse'       => '/account/%s/order/%d/pdf'
        ];
        $route->configure($config);

        // Get the reverse
        $parameters = [
            'name'      => 'olivia',
            'orderId'   => 42
        ];
        $result = $route->reverse($parameters);

        // Check the result
        $this->assert->string($result)->isEqualTo('/account/olivia/order/42/pdf');
    }







}

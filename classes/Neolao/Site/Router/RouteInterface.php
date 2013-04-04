<?php
namespace Neolao\Site\Router;

use \Neolao\Site\Request;

/**
 * Route interface
 */
interface RouteInterface
{
    /**
     * Configure the route
     *
     * @param   array   $parameters         Parameters
     */
    function configure(array $parameters);

    /**
     * Find the route based on the request
     *
     * @param   \Neolao\Site\Request    $request        Request instance
     * @return  bool                                    true if the request is updated and matches the route, false otherwise
     */
    function handleRequest(Request $request);

    /**
     * Reverse a route
     *
     * @param   array   $parameters         Parameters
     * @return  string                      Reverse path
     */
    function reverse(array $parameters = []);
}

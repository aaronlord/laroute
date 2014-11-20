<?php

namespace Lord\Laroute\Routes;

use Config;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Lord\Laroute\Routes\Exceptions\ZeroRoutesException;

class Collection extends \Illuminate\Support\Collection
{
    public function __construct(RouteCollection $routes)
    {
        $this->items = $this->parseRoutes($routes);
    }

    /**
     * Parse the routes into a jsonable output.
     *
     * @return array
     */
    protected function parseRoutes(RouteCollection $routes)
    {
        $this->guardAgainstZeroRoutes($routes);

        $results = array();

        foreach ($routes as $route) {
            $results[] = $this->getRouteInformation($route);
        }

        return array_values(array_filter($results));
    }

    /**
     * Throw an exception if there aren't any routes to process
     *
     * @throws ZeroRoutesException
     * @return void
     */
    protected function guardAgainstZeroRoutes(RouteCollection $routes)
    {
        if (count($routes) < 1) {
            throw new ZeroRoutesException("You don't have any routes!");
        }
    }

    /**
     * Get the route information for a given route.
     *
     * @param $route \Illuminate\Routing\Route
     *
     * @return array
     */
    protected function getRouteInformation(Route $route)
    {
        $host    = $route->domain();
        $methods = $route->getMethods();
        $uri     = $route->uri();
        $name    = $route->getName();
        $action  = $route->getActionName();
        $laroute = array_get($route->getAction(), 'laroute', true);

	    $filter = Config::get('laroute::config.filter');

	    $routeHasFilter = array_get($route->getAction(), $filter, false);

        if (($filter and !$routeHasFilter) or $laroute === false) {
            return null;
        }

        return compact('host', 'methods', 'uri', 'name', 'action');
    }

}

<?php

namespace App\View\Composers\Admin;

use Illuminate\Routing\Route;
use Illuminate\Contracts\View\View;
use Closure;

class AdminRouteMatchesComposer
{
    /**
     * The current route matcher resolver callback.
     *
     * @var \Closure
     */
    protected Closure $routeMatcher;

    /**
     * Create a new view composer instance.
     *
     * @param  \Illuminate\Routing\Route  $route
     */
    public function __construct(Route $route)
    {
        $this->routeMatcher = $this->getRouteMatcher($route);
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('routeMatches', $this->routeMatcher);
    }

    /**
     * Matches route names to the current route.
     *
     * Resource-Routes: (['users', 'orders', ...], {param}|null, true)
     * Single-Routes: (['users.index', 'orders.store', ...], {param}|null, false)
     * Param-Routes: (['users' => {param}, 'orders', ...], {param}|null, true|false)
     *
     * NOTE: Second parameter {param} is compared to the current route parameter.
     * NOTE: Second parameter {param} replaces the current route parameter if
     *       "Param-Routes" {param} is present.
     * NOTE: Third parameter is true by default, which indicates resource routes.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return \Closure
     */
    protected function getRouteMatcher(Route $route): Closure
    {
        $currentRouteName = str_replace(
            cms_route_name(), '', $route->getName()
        );

        $params = $route->parameters();
        $currentRouteIndexParam = end($params);

        $resourceMethod = null;

        foreach (resource_names('') as $value) {
            if (str_contains($currentRouteName, $value)) {
                $currentRouteName = str_replace($value, '', $currentRouteName);

                $resourceMethod = $value;
            }
        }

        return function ($routeNames, $routeParam = null, $byResource = true)
        use ($currentRouteName, $currentRouteIndexParam, $resourceMethod) {
            if (! $byResource) {
                $currentRouteName .= $resourceMethod;
            }

            foreach ((array) $routeNames as $key => $value) {
                if (is_string($key)) {
                    $routeName = $key;
                    $currentRouteIndexParam = $value;
                } else {
                    $routeName = $value;
                }

                if ($routeName == $currentRouteName
                    && (! $routeParam || $routeParam == $currentRouteIndexParam)
                ) {
                    return true;
                }
            }

            return false;
        };
    }
}

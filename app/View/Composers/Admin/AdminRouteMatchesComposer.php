<?php

namespace App\View\Composers\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->routeMatcher = $this->getRouteMatcher($request);
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
     * Resource-Routes: (['users', 'orders', ...], [routeParam => paramValue]|null, true)
     * Single-Routes: (['users.index', 'orders.store', ...], [routeParam => paramValue]|null, false)
     * Param-Routes: (['users' => {param}, 'orders', ...], [routeParam => paramValue]|null, true|false)
     *
     * NOTE: The second parameter is compared to the last route parameter if `routeParam` key is not present.
     * NOTE: The second parameter will be replaced by the current route parameter if
     *       "Param-Routes" {param} is present.
     * NOTE: Third parameter is true by default, which indicates resource routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Closure
     */
    protected function getRouteMatcher(Request $request): Closure
    {
        if (is_null($route = $request->route())) {
            return fn () => false;
        }

        $currentFullRouteName = $route->getName();

        $currentRouteName = str_replace(
            language() . '.' . cms_route_name(), '', $currentFullRouteName
        );

        if ($currentRouteName == $currentFullRouteName) {
            $currentRouteName = str_replace(cms_route_name(), '', $currentRouteName);
        }

        $params = $route->parameters();

        $resourceMethod = null;

        foreach (resource_names('') as $value) {
            if (str_contains($currentRouteName, $value)) {
                $currentRouteName = str_replace($value, '', $currentRouteName);

                $resourceMethod = $value;
            }
        }

        return function ($routeNames, $routeParam = null, $byResource = true)
        use ($currentRouteName, $params, $resourceMethod) {
            if (! $byResource) {
                $currentRouteName .= $resourceMethod;
            }

            if (is_array($routeParam)) {
                $currentRouteParam = $params[key($routeParam)] ?? 0;

                $routeParam = current($routeParam);
            } else {
                $currentRouteParam = end($params);
            }

            foreach ((array) $routeNames as $key => $routeName) {
                if (is_string($key)) {
                    $currentRouteParam = $routeName;

                    $routeName = $key;
                }

                if ($routeName == $currentRouteName
                    && (! $routeParam || $routeParam == $currentRouteParam)
                ) {
                    return true;
                }
            }

            return false;
        };
    }
}

<?php

namespace App\View\Composers\Admin;

use Illuminate\Routing\Route;
use Illuminate\Contracts\View\View;
use Closure;

class AdminRouteMatchesComposer
{
    /**
     * The route matcher callback.
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
        $this->routeMatcher = $this->setRouteMatcher($route);
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
     * Set the matcher of the current route to the specified routes.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return \Closure
     */
    protected function setRouteMatcher(Route $route): Closure
    {
        $currentRouteName = str_replace(
            cms_route_name(), '', $route->getName()
        );
        $currentRouteIndexParam = current($route->parameters());

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

                if (! $routeParam
                    && $routeName == $currentRouteName
                    || $routeParam == $currentRouteIndexParam
                    && $routeName == $currentRouteName
                ) {
                    return true;
                }
            }

            return false;
        };
    }
}

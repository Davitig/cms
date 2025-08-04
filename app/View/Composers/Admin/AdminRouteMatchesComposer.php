<?php

namespace App\View\Composers\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

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
    public function __construct(protected Route $route)
    {
        $this->routeMatcher = $this->getRouteMatcher();
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
     * Base-Routes: (['users', 'orders', ...], [routeParam => paramValue]|null, true)
     * Single-Routes: (['users.index', 'orders.store', ...], [routeParam => paramValue]|null, false)
     * Param-Routes: (['users' => {param}, 'orders', ...], [routeParam => paramValue]|null, true|false)
     *
     * NOTE: The second parameter indicates whether the routeNames should match to the currentRouteName by segments.
     * NOTE: The third parameter is compared to the last route parameter if `routeParam` key is not present.
     * NOTE: The third parameter will be replaced by the current route parameter if "Param-Routes" {param} is present.
     *
     * @return \Closure
     */
    protected function getRouteMatcher(): Closure
    {
        if (is_null($currentRouteName = $this->route->getName())) {
            return fn () => false;
        }

        $routeParams = $this->route->parameters();

        $currentRouteName = str($currentRouteName)->replaceStart(
            config('language.route_name') . '.', ''
        );

        $currentRouteName = str($currentRouteName)->replaceStart(cms_route_name(), '');

        $currentRouteNameSegments = explode('.', $currentRouteName);

        return static function ($routeNames, $matchBase = true, $routeParam = null)
        use ($currentRouteName, $routeParams, $currentRouteNameSegments) {
            if (is_array($routeParam)) {
                $currentRouteParam = $routeParams[key($routeParam)] ?? 0;

                $routeParam = reset($routeParam);
            } else {
                $currentRouteParam = end($routeParams);
            }

            foreach ((array) $routeNames as $key => $routeName) {
                if (is_string($key)) {
                    $currentRouteParam = $routeName;

                    $routeName = $key;
                }

                if ($matchBase) {
                    $routeNameSegmentsCount = max(1, substr_count($routeName, '.') + 1);

                    $currentRouteName = implode('.', array_slice(
                        $currentRouteNameSegments, 0, $routeNameSegmentsCount
                    ));
                }

                if ($routeName == $currentRouteName &&
                    (! $routeParam || $routeParam == $currentRouteParam)) {
                    return true;
                }
            }

            return false;
        };
    }
}

<?php

namespace Tests\Feature;

use App\Models\Permission;

trait RoutesTrait
{
    protected function getAllCMSRouteNames(): array
    {
        $routeNames = [];

        $cmsSlug = cms_route_name();

        foreach ($this->app['router']->getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, $cmsSlug)
                || ! empty($route->parameterNames())
                || ! in_array('GET', $route->methods())) {
                continue;
            }

            $name = str($name)->chopStart($cmsSlug)->toString();

            $baseRouteName = str($name)->before('.')->toString();

            if ($baseRouteName) {
                $routeNames[$baseRouteName][] = $name;
            } else {
                $routeNames[$name] ??= [];
                array_unshift($routeNames[$name], $name);
            }
        }

        return array_filter(array_diff_key(
            $routeNames,
            array_flip(Permission::$routeGroupsHidden),
            array_flip(Permission::$routeGroupsAllowed)
        ), function ($routes) {
            return array_filter($routes, function ($route) {
                return ! in_array($route, Permission::$routeNamesHidden)
                    && ! in_array($route, Permission::$routeNamesAllowed);
            });
        });
    }
}

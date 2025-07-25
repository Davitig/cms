<?php

namespace Tests\Feature;

use App\Models\Permission;

trait InteractsWithRouter
{
    protected function getAllCMSRouteNames(): array
    {
        $routeNames = [];

        $prefix = cms_route_name();

        if (! language()->isEmpty()) {
            $prefix = $this->app['config']->get('language.route_name') . '.' . $prefix;
        }

        foreach ($this->app['router']->getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, $prefix) || ! in_array('GET', $route->methods())) {
                continue;
            }

            $name = str($name)->chopStart($prefix)->toString();

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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsUser\CmsUserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminPermissionController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Permission $model, protected Request $request) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return ['cms.fullAccess'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['roles'] = (new CmsUserRole)->fullAccess(false)
            ->pluck('role', 'id')
            ->toArray();

        $data['activeRoleId'] = $this->request->get('role', $roleId = key($data['roles']));

        if (! array_key_exists($data['activeRoleId'], $data['roles'])) {
            $data['activeRoleId'] = $roleId;
        }

        $data['activeRole'] = $data['roles'][$data['activeRoleId']];

        $data['activeRoutes'] = $this->model->roleId($data['activeRoleId'])
            ->pluck('route_name')
            ->toArray();

        $data['routeGroups'] = array_filter(array_diff_key(
            $this->getAllCMSRouteNames(),
            array_flip(Permission::$routeGroupsHidden),
            array_flip(Permission::$routeGroupsAllowed)
        ), function ($routes) {
            return array_filter($routes, function ($route) {
                return ! in_array($route, Permission::$routeNamesHidden) &&
                    ! in_array($route, Permission::$routeNamesAllowed);
            });
        });

        return view('admin.permissions.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        if (is_null($roleId = $this->request->get('role_id'))) {
            return redirect(cms_route('permissions.index'))
                ->with('alert', fill_data(false, trans('general.invalid_input')));
        }

        $this->model->clear($roleId);

        if (! (new CmsUserRole)->whereKey($roleId)->fullAccess(false)->exists()) {
            return redirect(cms_route('permissions.index'))
                ->with('alert', fill_data(false, trans('general.invalid_input')));
        }

        $attributes = [];

        foreach ((array) $this->request->get('permissions') as $groupName => $routes) {
            foreach ((array) $routes as $routeName) {
                if (in_array($groupName, Permission::$routeGroupsHidden) ||
                    in_array($groupName, Permission::$routeGroupsAllowed) ||
                    in_array($routeName, Permission::$routeNamesHidden) ||
                    in_array($routeName, Permission::$routeNamesAllowed)) {
                    continue;
                }

                $attributes['cms_user_role_id'] = $roleId;
                $attributes['route_name'] = $routeName;

                $this->model->create($attributes);
            }
        }

        return redirect(cms_route('permissions.index', ['role' => $roleId]))
            ->with('alert', fill_data(true, trans('general.saved')));
    }

    /**
     * Get all CMS route names.
     *
     * @return array
     */
    protected function getAllCMSRouteNames(): array
    {
        $routeNames = [];

        $prefix = config('language.route_name') . '.' . cms_route_name();

        foreach (app('router')->getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, $prefix)) {
                continue;
            }

            $routeNames[] = str($name)->chopStart($prefix)->toString();
        }

        return $this->groupRouteNames($routeNames);
    }

    /**
     * Group the route names.
     *
     * @param  array  $routeNames
     * @return array
     */
    protected function groupRouteNames(array $routeNames): array
    {
        $routeNameList = [];

        foreach ($routeNames as $routeName) {
            $baseRouteName = str($routeName)->before('.')->toString();

            if ($baseRouteName) {
                $groupedRouteName = $this->getGroupedRouteName($routeName);

                if (is_int(key($groupedRouteName))) {
                    $routeNameList[$baseRouteName][] = current($groupedRouteName);
                } else {
                    $routeNameList[$baseRouteName]
                    [key($groupedRouteName)][] = current($groupedRouteName);
                }
            } else {
                $routeNameList[$routeName] ??= [];
                array_unshift($routeNameList[$routeName], $routeName);
            }
        }

        array_walk($routeNameList, fn (&$array) => uksort($array, function ($a, $b) {
            return is_string($a) ? (is_string($b) ? 0 : 1) : 0;
        }));

        return $routeNameList;
    }

    /**
     * Get the grouped route name.
     *
     * @param  string  $routeName
     * @param  int  $startLevel
     * @return string|array
     */
    protected function getGroupedRouteName(string $routeName, int $startLevel = 1): string|array
    {
        $startLevel = abs($startLevel);

        if (substr_count($routeName, '.') < (1 + $startLevel)) {
            return [$routeName];
        }

        if (! $startLevel) {
            return [str($routeName)->before('.')->toString() => $routeName];
        }

        $subRouteName = $routeName;

        for ($i = 0; $i < $startLevel; $i++) {
            $subRouteName = str($subRouteName)->after('.')->toString();
        }

        if (! substr_count($routeName, '.')) {
            return [$routeName];
        }

        return [str($subRouteName)->before('.')->toString() => $routeName];
    }
}

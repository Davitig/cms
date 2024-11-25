<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsUserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminPermissionsController extends Controller implements HasMiddleware
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
        return ['cms.withFullAccess'];
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

        $data['currentRoutes'] = $this->model->roleId($data['activeRoleId'])
            ->pluck('route_name')
            ->toArray();

        $data['routeGroups'] = array_filter(array_diff_key(
            $this->getAllCMSRouteNames(),
            array_flip(Permission::$routeGroupsHidden),
            array_flip(Permission::$routeGroupsAllowed)
        ), function ($routes) {
            return array_filter($routes, function ($route) {
                return ! in_array($route, Permission::$routeNamesHidden)
                    && ! in_array($route, Permission::$routeNamesAllowed);
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
        $this->model->clear($roleId = $this->request->get('role_id'));

        if (! (new CmsUserRole)->whereKey($roleId)->fullAccess(false)->exists()) {
            return redirect(cms_route('permissions.index'));
        }

        $input = $this->request->get('permissions', []);

        $attributes = [];

        foreach ($input as $groupName => $routes) {
            foreach ($routes as $routeName) {
                if (in_array($groupName, Permission::$routeGroupsHidden)
                    || in_array($groupName, Permission::$routeGroupsAllowed)
                    || in_array($routeName, Permission::$routeNamesHidden)
                    || in_array($routeName, Permission::$routeNamesAllowed)
                ) {
                    continue;
                }

                $attributes['cms_user_role_id'] = $roleId;
                $attributes['route_name'] = $routeName;

                $this->model->create($attributes);
            }
        }

        return redirect(cms_route('permissions.index', ['role' => $roleId]))
            ->with('alert', fill_data('success', trans('general.saved')));
    }

    /**
     * Get all CMS route names.
     *
     * @return array
     */
    protected function getAllCMSRouteNames(): array
    {
        $routeNames = [];

        $cmsSlug = cms_route_name();

        foreach (app('router')->getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, $cmsSlug)) {
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

        return $routeNames;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminPermissionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Permission $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->checkAccess();

        $data['roles'] = [];

        foreach ((array) user_roles() as $key => $value) {
            if ($key == 'admin') {
                continue;
            }

            $data['roles'][$key] = $value;
        }

        $data['current'] = [];

        if ($role = $this->request->get('role', key($data['roles']))) {
            $data['current'] = $this->model->role($role)
                ->pluck('route_name')
                ->toArray();
        }

        $data['routeGroups'] = array_diff_key(
            $this->getAllRouteNames(), array_flip(Permission::$routeGroupsHidden)
        );

        $data['namesDisallowed'] = Permission::$routeNamesHidden;

        return view('admin.permissions.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->checkAccess();

        $this->model->clear($role = $this->request->get('role'));

        $input = $this->request->get('permissions', []);

        $attributes = [];

        foreach ($input as $groupName => $routes) {
            foreach ($routes as $routeName) {
                if (in_array($groupName, Permission::$routeGroupsHidden)
                    || in_array($routeName, Permission::$routeNamesHidden)
                ) {
                    continue;
                }

                $attributes['role'] = $role;
                $attributes['route_name'] = $routeName;

                $this->model->create($attributes);
            }
        }

        return redirect(cms_route('permissions.index', ['role' => $role]))
            ->with('alert', fill_data('success', trans('general.saved')));
    }

    /**
     * Determine if the user has access to the given route
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    protected function checkAccess()
    {
        $user = $this->request->user('cms');

        if (! $user->isAdmin()) {
            throw new AccessDeniedHttpException('Forbidden');
        }
    }

    /**
     * Get all cms route names.
     *
     * @return array
     */
    protected function getAllRouteNames()
    {
        $routes = app('router')->getRoutes()->getRoutesByName();

        $routeNames = [];

        $prevRouteName = null;

        $cmsSlug = cms_route_name_prefix('');

        foreach ($routes as $name => $route) {
            if (! str_contains($name, $cmsSlug)) {
                continue;
            }

            $name = str_replace($cmsSlug, '', $name);

            if ($prevRouteName == $name) {
                continue;
            }

            $baseRouteName = explode('.', substr($name, 0, strrpos($name, '.')));

            $routeNames[$baseRouteName[0]][] = $prevRouteName = $name;
        }

        return $routeNames;
    }
}

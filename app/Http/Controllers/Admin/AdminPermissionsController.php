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

        $routeNames = array_diff_key(
            $this->getAllRouteNames(),
            array_flip(Permission::$routeGroupsHidden)
        );

        // ksort($routeNames);

        $data['routes'] = $routeNames;

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

        foreach ((array) $input as $value) {
            if (in_array(key($value), Permission::$routeGroupsHidden)){
                continue;
            }

            $attributes['role'] = $role;
            $attributes['route_name'] = current($value);

            $this->model->create($attributes);
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
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Get all cms route names.
     *
     * @return array
     */
    protected function getAllRouteNames()
    {
        $routes = app('router')->getRoutes();

        $routeNames = [];

        $prevRouteName = null;

        $cmsSlug = cms_slug();

        foreach ($routes as $route) {
            $routeName = $route->getName();

            if (! is_null($routeName) && str_contains($route->getPrefix(), $cmsSlug)) {
                if ($prevRouteName == $routeName) continue;

                $baseRouteName = explode('.', substr(
                    $routeName, 0, strrpos($routeName, '.')
                ));

                $routeNames[$baseRouteName[0]][] = $routeName;

                $prevRouteName = $routeName;
            }
        }

        return $routeNames;
    }
}

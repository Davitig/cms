<?php

namespace App\Http\Controllers\Admin\CmsUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsUserRoleRequest;
use App\Models\CmsUser\CmsUser;
use App\Models\CmsUser\CmsUserRole;
use App\Models\Permission;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminCmsUserRoleController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CmsUserRole $model, protected Guard $guard) {}

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
        $data['items'] = $this->model->paginate(50);

        $data['items']->each(function ($item) {
            $item->permissions_count = (new Permission)->roleId($item->id)->count();

            $item->cms_users_count = (new CmsUser)->roleId($item->id)->count();

            $item->cms_users = (new CmsUser)->roleId($item->id)
                ->inRandomOrder()
                ->limit($item->cms_users_count > 3 ? 3 : 4)
                ->get();
        });

        return view('admin.cms-user-roles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['current'] = $this->model;

        return view('admin.cms-user-roles.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CmsUserRoleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CmsUserRoleRequest $request)
    {
        $model = $this->model->create($request->all());

        return redirect(cms_route('cmsUserRoles.edit', [$model->id]))
            ->with('alert', fill_data(true, trans('general.created')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $data['current'] = $this->model->findOrFail($id);

        $data['current']->permissions_count = (new Permission)->roleId($id)->count();

        return view('admin.cms-user-roles.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CmsUserRoleRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(CmsUserRoleRequest $request, string $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $errorCode = 0;

        $data = fill_data(true, trans('database.deleted'));

        try {
            $this->model->findOrFail($id)->delete();
        } catch (QueryException $e) {
            $errorCode = (string) ($e->errorInfo[1] ?? null);

            $data = fill_data(
                (int) ! $errorCode,
                trans('database.' . ($errorCode ? 'error.' . $errorCode : 'deleted'))
            );
        }

        if (request()->expectsJson()) {
            return response()->json($data, $errorCode ? 403 : 200);
        }

        return back()->with('alert', $data);
    }
}

<?php

namespace App\Http\Controllers\Admin\CmsUser;

use App\Http\Controllers\Controller;
use App\Models\CmsUser\CmsUser;
use App\Models\CmsUser\CmsUserPreference;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUserPreferenceController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CmsUser $model) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function (Request $request, Closure $next) {
                if (! $request->user('cms')->hasFullAccess() &&
                    $request->user('cms')->id != $request->route('cms_user')) {
                    throw new AccessDeniedHttpException('Forbidden');
                }

                return $next($request);
            })
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $id)
    {
        $data['current'] = $this->model->findOrFail($id);

        return view('admin.cms-users.preferences.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $data = $request->merge([
            'cms_user_id' => $cmsUserId = $request->user('cms')->id
        ])->all();

        foreach (['horizontal_menu', 'ajax_form'] as $key) {
            data_fill($data, $key, 0);
        }

        if ((new CmsUserPreference)->cmsUserId($cmsUserId)->exists()) {
            (new CmsUserPreference)->cmsUserId($cmsUserId)->firstOrFail()->update($data);
        } else {
            (new CmsUserPreference)->create($data);
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated')));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }
}

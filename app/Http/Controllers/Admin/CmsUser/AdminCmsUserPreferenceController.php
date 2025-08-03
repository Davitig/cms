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
    public function __construct(protected CmsUserPreference $model) {}

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
     * @param  string  $cmsUserId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $cmsUserId)
    {
        $data['current'] = $this->model->cmsUserId($cmsUserId)->firstOr(
            fn () => $this->model
        );

        $data['cmsUser'] = (new CmsUser)->findOrFail($cmsUserId);

        return view('admin.cms-users.preferences.index', $data);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $cmsUserId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, string $cmsUserId)
    {
        $input = $request->except('cms_user_id');

        foreach (['horizontal_menu', 'ajax_form'] as $key) {
            data_fill($input, $key, 0);
        }

        if (! is_null($model = $this->model->cmsUserId($cmsUserId)->first())) {
            $model->update($input);
        } else {
            $input['cms_user_id'] = $cmsUserId;

            $this->model->create($input);
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated'), $input));
    }
}

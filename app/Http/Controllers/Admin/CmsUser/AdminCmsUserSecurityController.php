<?php

namespace App\Http\Controllers\Admin\CmsUser;

use App\Http\Controllers\Controller;
use App\Models\CmsUser\CmsUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminCmsUserSecurityController extends Controller implements HasMiddleware
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

        return view('admin.cms-users.security.index', $data);
    }

    /**
     * Update the current password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, string $id)
    {
        $data = $request->validate([
            'current_password' => 'current_password:cms',
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed']
        ]);

        $this->model->whereKey($id)->update(['password' => bcrypt($data['password'])]);

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated')));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }
}

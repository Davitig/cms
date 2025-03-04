<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLockscreenController extends Controller
{
    /**
     * Get the lockscreen response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $request->session()->flash('includeLockscreen', 1);

        if (! $request->user('cms')->hasLockScreen()) {
            return redirect(cms_url());
        }

        return view('admin.app');
    }

    /**
     * Set the lockscreen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function lock(Request $request)
    {
        $request->user('cms')->lockScreen();

        if ($request->expectsJson()) {
            return response()->json([
                'result' => true, 'view' => view('admin.lockscreen')->render()
            ]);
        }

        return redirect(cms_route('dashboard'));
    }

    /**
     * Handle a lockscreen unlock request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function unlock(Request $request)
    {
        $guard = Auth::guard('cms');

        $isValid = false;

        if ($password = $request->get('password')) {
            $isValid = $guard->getProvider()->validateCredentials(
                $guard->user(), compact('password')
            );
        }

        if ($isValid) {
            $guard->user()->unlockScreen();

            if ($request->expectsJson()) {
                return response()->json(fill_data(true));
            }

            return redirect()->intended(cms_route('dashboard'));
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(false, trans('auth.invalid.password')));
        }

        return back()->withErrors(trans('auth.invalid.password'));
    }
}

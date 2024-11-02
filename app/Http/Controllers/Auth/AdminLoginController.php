<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * {@inheritDoc}
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect(cms_route('login'));
    }

    /**
     * Get the authentication guard.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return Auth::guard('cms');
    }

    /**
     * {@inheritDoc}
     */
    public function redirectPath()
    {
        return cms_url($this->redirectTo);
    }

    /**
     * Get the lockscreen response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLockscreen(Request $request)
    {
        $request->session()->flash('includeLockscreen', 1);

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
    public function setLockscreen(Request $request)
    {
        $this->guard()->user()->lockScreen();

        if ($request->expectsJson()) {
            return response()->json([
                'result' => true, 'view' => view('admin.lockscreen')->render()
            ]);
        }

        return redirect(cms_route('dashboard'));
    }

    /**
     * Handle a lockscreen request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postLockscreen(Request $request)
    {
        $isValid = false;

        if ($request->filled('password')) {
            $isValid = $this->guard()->getProvider()->validateCredentials(
                $this->guard()->user(),
                $request->all('password')
            );
        }

        if ($isValid) {
            $this->guard()->user()->unlockScreen();

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

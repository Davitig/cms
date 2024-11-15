<?php

namespace App\Http\Controllers\Admin\Auth;

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
        Auth::guard('cms')->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect(cms_route('login'));
    }

    /**
     * Get the register / login redirect path.
     *
     * @return string
     */
    public function redirectTo(): string
    {
        return cms_url('/');
    }

    /**
     * {@inheritDoc}
     */
    protected function guard()
    {
        return Auth::guard('cms');
    }
}

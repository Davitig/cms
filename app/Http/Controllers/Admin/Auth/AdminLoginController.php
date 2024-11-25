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
    protected function loggedOut(Request $request)
    {
        return $request->wantsJson()
            ? response()->json([], 204)
            : redirect(cms_route('login'));
    }

    /**
     * {@inheritDoc}
     */
    public function redirectPath(): string
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

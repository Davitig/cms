<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Auth\LoginController as Controller;
use Illuminate\Http\JsonResponse;
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
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|void
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->suspended) {
            return;
        }

        $data = fill_data(false, 'Account has been suspended.');

        if (($response = $this->logout($request)) instanceof JsonResponse) {
            return $response->setData($data)->setStatusCode(403);
        }

        return $response->with('alert', $data);
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

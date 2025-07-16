<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminWebSettingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return ['cms.fullAccess'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['web_settings'] = (new WebSetting)->getSettings();

        return view('admin.web-settings.index', $data);
    }

    /**
     * Save the resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        (new WebSetting)->saveSettings($request->all());

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }
}

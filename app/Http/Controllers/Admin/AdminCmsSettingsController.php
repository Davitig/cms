<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCmsSettingsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.cms_settings.index');
    }

    /**
     * Update the "cms_settings" table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $userId = $request->user('cms')->id;
        $attributes['horizontal_menu'] = $request->filled('horizontal_menu') ? 1 : 0;

        $table = app('db')->table('cms_settings');

        if ($table->where('cms_user_id', $userId)->exists()) {
            $table->update($attributes);
        } else {
            $attributes['cms_user_id'] = $userId;

            $table->insert($attributes);
        }

        return redirect(cms_route('cmsSettings.index'))
            ->with('alert', fill_data('success', trans('general.updated')));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
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
        $data = $request->merge([
            'cms_user_id' => $cmsUserId = $request->user('cms')->id
        ])->all();

        boolify($data, ['horizontal_menu']);

        if ((new CmsSetting)->cmsUserId($cmsUserId)->exists()) {
            (new CmsSetting)->cmsUserId($cmsUserId)->firstOrFail()->update($data);
        } else {
            (new CmsSetting)->create($data);
        }

        return redirect(cms_route('cmsSettings.index'))
            ->with('alert', fill_data('success', trans('general.updated')));
    }
}

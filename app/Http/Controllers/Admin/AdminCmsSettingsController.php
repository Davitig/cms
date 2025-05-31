<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminCmsSettingsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [
            'sidebarDirection' => [
                'left-sidebar'  => 'Left',
                'right-sidebar' => 'Right'
            ],
            'sidebarPosition' => [
                'fixed'            => 'Fixed',
                'fixed collapsed'  => 'Fixed & Collapsed',
                'static'           => 'Static',
                'static collapsed' => 'Static & Collapsed'
            ],
            'alertPosition' => [
                'top-right'         => 'Top Right',
                'top-left'          => 'Top Left',
                'top-center'        => 'Top Center',
                'top-full-width'    => 'Top Full Width',
                'bottom-right'      => 'Bottom Right',
                'bottom-left'       => 'Bottom Left',
                'bottom-center'     => 'Bottom Center',
                'bottom-full-width' => 'Bottom Full Width'
            ],
            'lockscreen' => [
                '0'       => 'Disable',
                '30000'   => '30 Seconds',
                '60000'   => '1 Minute',
                '300000'  => '5 Minutes',
                '600000'  => '10 Minutes',
                '1200000' => '20 Minutes',
                '1800000' => '30 Minutes',
                '3600000' => '1 Hour'
            ]
        ];

        return view('admin.cms_settings.index', $data);
    }

    /**
     * Update the "cms_settings" table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $columns = array_flip(Schema::getColumnListing('cms_settings'));
        unset($columns['id']);

        $attributes = $request->all();
        $checkboxes = ['layout_boxed', 'horizontal_menu_click', 'horizontal_menu_type', 'ajax_form'];

        foreach ($checkboxes as $value) {
            if (! isset($attributes[$value])) {
                $attributes[$value] = '';
            }
        }

        $attributes['horizontal_menu'] = $request->filled('horizontal_menu') ? 1 : 0;
        $attributes['cms_user_id'] = $userId = $request->user('cms')->id;

        $attributes = array_intersect_key($attributes, $columns);

        $table = app('db')->table('cms_settings');

        if ($table->where('cms_user_id', $userId)->exists()) {
            $table->update($attributes);
        } else {
            $table->insert($attributes);
        }

        return redirect(cms_route('cmsSettings.index', ['tab' => $request->get('tab', 1)]))
            ->with('alert', fill_data('success', trans('general.updated')));
    }
}

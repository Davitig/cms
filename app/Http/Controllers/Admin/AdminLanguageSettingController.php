<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language\LanguageSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminLanguageSettingController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected LanguageSetting $model) {}

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
        $data['current'] = $this->model->getSettings();

        return view('admin.settings.language', $data);
    }

    /**
     * Save the resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $this->model->saveSettings($input = $request->all());

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated'), $input));
    }
}

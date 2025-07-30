<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;

class AdminSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.settings.index');
    }
}

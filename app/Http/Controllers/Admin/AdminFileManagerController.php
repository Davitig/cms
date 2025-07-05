<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminFileManagerController extends Controller
{
    /**
     * Display a file manager.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.file-manager.index');
    }
}

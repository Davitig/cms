<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageFile;

class WebPageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Page $page)
    {
        $data['current'] = $page;

        $data['files'] = (new PageFile)->getFiles($page->id);

        return view('web.page', $data);
    }
}

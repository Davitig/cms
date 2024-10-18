<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Models\Page;
use Models\PageFile;

class WebPageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Models\Page  $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Page $page)
    {
        $data['current'] = $page;

        $data['files'] = (new PageFile)->getFiles($page->id);

        return view('web.page', $data);
    }
}

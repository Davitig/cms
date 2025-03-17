<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page\PageFile;

class WebPageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages)
    {
        $data['current'] = $page = last($pages);

        $data['files'] = (new PageFile)->getFiles($page->id);

        return view('web.page', $data);
    }
}

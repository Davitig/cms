<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;

class WebSearchController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page\Page  $page
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Page $page)
    {
        $data['current'] = $page;

        // search action...

        return view('web.search', $data);
    }
}

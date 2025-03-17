<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WebSearchController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages)
    {
        $data['current'] = last($pages);

        // search action...

        return view('web.search', $data);
    }
}

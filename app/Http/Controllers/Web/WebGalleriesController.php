<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Gallery\Gallery;
use Illuminate\Contracts\Database\Eloquent\Builder;

class WebGalleriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Gallery\Gallery  $model
     */
    public function __construct(protected Gallery $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages, Collection $collection)
    {
        $data['current'] = last($pages);

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.gallery', $data);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Page;

class WebGalleriesController extends Controller
{
    /**
     * The Gallery instance.
     *
     * @var \App\Models\Gallery
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Gallery  $model
     * @return void
     */
    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Page  $page
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Page $page, Collection $collection)
    {
        $data['current'] = $page;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.gallery', $data);
    }
}

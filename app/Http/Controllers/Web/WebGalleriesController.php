<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;

class WebGalleriesController extends Controller
{
    /**
     * The Gallery instance.
     *
     * @var \App\Models\Gallery\Gallery
     */
    protected Gallery $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Gallery\Gallery  $model
     * @return void
     */
    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page, \App\Models\Collection>  $models
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(array $models)
    {
        [$data['current'], $collection] = $models;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.gallery', $data);
    }
}

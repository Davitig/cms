<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;
use App\Models\Photo;

class WebPhotosController extends Controller
{
    /**
     * The Photo instance.
     *
     * @var \App\Models\Photo
     */
    protected Photo $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Photo  $model
     * @return void
     */
    public function __construct(Photo $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page, \App\Models\Collection>  $models
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(array $models, Gallery $gallery)
    {
        [$data['parent'], $collection] = $models;

        $data['current'] = $gallery;

        $data['items'] = $this->model->getPublicGallery($gallery);

        return view('web.photos', $data);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;
use App\Models\Video;

class WebVideosController extends Controller
{
    /**
     * The Video instance.
     *
     * @var \App\Models\Video
     */
    protected Video $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Video  $model
     */
    public function __construct(Video $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page, \App\Models\Collection>  $models
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $models, Gallery $gallery)
    {
        [$data['parent'], $collection] = $models;

        $data['current'] = $gallery;

        $data['items'] = $this->model->getPublicGallery($gallery);

        return view('web.videos', $data);
    }
}

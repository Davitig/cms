<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;
use App\Models\Video;

class WebVideosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Video  $model
     */
    public function __construct(protected Video $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  array  $pages
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages, Gallery $gallery)
    {
        $data['parent'] = last($pages);

        $data['current'] = $gallery;

        $data['items'] = $this->model->getPublicGallery($gallery);

        return view('web.videos', $data);
    }
}

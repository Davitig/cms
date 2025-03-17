<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;
use App\Models\Photo;

class WebPhotosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Photo  $model
     */
    public function __construct(protected Photo $model) {}

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

        return view('web.photos', $data);
    }
}

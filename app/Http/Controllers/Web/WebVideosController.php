<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Models\Gallery;
use Models\Video;

class WebVideosController extends Controller
{
    /**
     * The Video instance.
     *
     * @var \Models\Video
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @param  \Models\Video  $model
     * @return void
     */
    public function __construct(Video $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Models\Gallery  $gallery
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Gallery $gallery)
    {
        $data['current'] = $gallery;

        $data['items'] = $this->model->getPublicGallery($gallery);

        return view('web.videos', $data);
    }
}

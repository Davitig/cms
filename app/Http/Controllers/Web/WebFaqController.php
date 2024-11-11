<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class WebFaqController extends Controller
{
    /**
     * The Faq instance.
     *
     * @var \App\Models\Faq
     */
    protected Faq $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Faq  $model
     */
    public function __construct(Faq $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page, \App\Models\Collection>  $models
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $models)
    {
        [$data['current'], $collection] = $models;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.faq', $data);
    }

    /**
     * The specified resource is not available.
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show()
    {
        abort(404);
    }
}

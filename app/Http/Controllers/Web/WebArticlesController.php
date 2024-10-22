<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleFile;

class WebArticlesController extends Controller
{
    /**
     * The Article instance.
     *
     * @var \App\Models\Article
     */
    protected Article $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Article  $model
     * @return void
     */
    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page, \App\Models\Collection>  $models
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(array $models)
    {
        [$data['current'], $collection] = $models;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.articles', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page, \App\Models\Collection>  $models
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(array $models, string $slug)
    {
        [$data['parent'], $collection] = $models;

        $data['current'] = $this->model->byCollectionSlug($collection->id, $slug)->firstOrFail();

        $data['files'] = (new ArticleFile)->getFiles($data['current']->id);

        return view('web.article', $data);
    }
}

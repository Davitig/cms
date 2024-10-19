<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleFile;
use App\Models\Collection;
use App\Models\Page;

class WebArticlesController extends Controller
{
    /**
     * The Article instance.
     *
     * @var \App\Models\Article
     */
    protected $model;

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
     * @param  \App\Models\Page  $page
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Page $page, Collection $collection)
    {
        $data['current'] = $page;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.articles', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Page $page, $slug)
    {
        $data['parent'] = $page;

        $data['current'] = $this->model->byCollectionSlug($page->type_id, $slug)->firstOrFail();

        $data['files'] = (new ArticleFile)->getFiles($data['current']->id);

        return view('web.article', $data);
    }
}

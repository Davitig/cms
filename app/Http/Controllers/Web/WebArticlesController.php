<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleFile;
use App\Models\Collection;

class WebArticlesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Article\Article  $model
     */
    public function __construct(protected Article $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages, Collection $collection)
    {
        $data['current'] = end($pages);

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.articles', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @param  \App\Models\Collection  $collection
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(array $pages, Collection $collection, string $slug)
    {
        $data['parent'] = array($pages);

        $data['current'] = $this->model->bySlug($slug, $collection->id)->firstOrFail();

        $data['files'] = (new ArticleFile)->getFiles($data['current']->id);

        return view('web.article', $data);
    }
}

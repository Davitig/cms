<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFileController as Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleFile;
use Illuminate\Http\Request;

class AdminArticleFileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(ArticleFile $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $articleId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $articleId)
    {
        return view('admin.collections.articles.files.index', $this->indexData(
            $articleId, new Article
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $articleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(string $articleId)
    {
        return $this->createData(
            $articleId,
            'admin.collections.articles.files.create',
            cms_route('articles.files.index', [$articleId])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $articleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, string $articleId)
    {
        return $this->storeData(
            $request,
            $articleId,
            'admin.collections.articles.files.item',
            cms_route('articles.files.index', [$articleId])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $articleId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $articleId, string $id)
    {
        return $this->editData(
            $articleId,
            $id,
            'admin.collections.articles.files.edit',
            cms_route('articles.files.index', [$articleId])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $articleId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, string $articleId, string $id)
    {
        return $this->updateData($request, $articleId, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $articleId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $articleId, string $id)
    {
        return $this->destroyData($articleId, $id);
    }
}

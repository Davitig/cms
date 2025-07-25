<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Models\CollectionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article\Article;
use App\Models\Collection;

class AdminArticleController extends Controller
{
    use InteractsWithPosition, InteractsWithVisibility, Transferable;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Article $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $collectionId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $collectionId)
    {
        $data['parent'] = (new Collection)->byType(CollectionType::ARTICLE)
            ->findOrFail($collectionId);

        $data['similarCollections'] = (new Collection)->byType(CollectionType::ARTICLE)
            ->withCount('articles')
            ->limit(50)
            ->get();

        $data['items'] = $this->model->filesExists()->getAdminCollection($data['parent']);

        return view('admin.collections.articles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $collectionId
     * @return \Illuminate\Contracts\View\View
     */
    public function create(string $collectionId)
    {
        $data['current'] = $this->model;
        $data['current']->collection_id = $collectionId;

        return view('admin.collections.articles.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ArticleRequest  $request
     * @param  string  $collectionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ArticleRequest $request, string $collectionId)
    {
        $input = $request->all();
        $input['collection_id'] = $collectionId;

        $model = $this->model->create($input);

        $model->languages()->createMany(apply_languages($input));

        return redirect(cms_route('articles.edit', [$collectionId, $model->id]))
            ->with('alert', fill_data(true, trans('general.created')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $collectionId, string $id)
    {
        $data['items'] = $this->model->whereKey($id)
            ->forAdmin(null, false)
            ->getOrFail();

        $data['current'] = $data['items']->first();

        return view('admin.collections.articles.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ArticleRequest  $request
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(ArticleRequest $request, string $collectionId, string $id)
    {
        $model = tap($this->model->findOrFail($id))->update($input = $request->all());

        if (! language()->isEmpty()) {
            $model->languages()->updateOrCreate(apply_languages(), $input);
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $collectionId, string $id)
    {
        $result = $this->model->findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data($result, trans('database.deleted')));
        }

        return back()->with('alert', fill_data($result, trans('database.deleted')));
    }
}

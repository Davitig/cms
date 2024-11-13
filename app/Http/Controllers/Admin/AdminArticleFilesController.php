<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleFile;
use Illuminate\Http\Request;

class AdminArticleFilesController extends Controller
{
    use Positionable, VisibilityTrait, LanguageRelationsTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected ArticleFile $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $articleId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $articleId)
    {
        $data['foreignModels'] = (new Article)->where('id', $articleId)
            ->joinLanguage(false)
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($articleId)->paginate(24);

        return view('admin.collections.articles.files.index', $data);
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
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;
            $data['current']->article_id = $articleId;

            return response()->json([
                'result' => true,
                'view' => view('admin.collections.articles.files.create', $data)->render()
            ]);
        }

        return redirect(cms_route('articles.files.index', [$articleId]));
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
        $input = $request->all();
        $input['article_id'] = $articleId;

        $model = $this->model->create($input);

        $this->createLanguageRelations('languages', $input, $model->id, true);

        if ($request->expectsJson()) {
            $view = view('admin.collections.articles.files.item', [
                'item' => $model,
                'itemInput' => $input
            ])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
            );
        }

        return redirect(cms_route('articles.files.index', [$articleId]));
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        abort(404);
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
        if ($this->request->expectsJson()) {
            $data['items'] = $this->model->joinLanguage(false)
                ->where('id', $id)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view('admin.collections.articles.files.edit', $data)->render()
            ]);
        }

        return redirect(cms_route('articles.files.index', [$articleId]));
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
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect(cms_route('articles.files.index', [$articleId]));
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
        $this->model->destroy($this->request->get('ids', $id));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

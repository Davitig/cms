<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleFile;

class AdminArticleFilesController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * The ArticleFile instance.
     *
     * @var \App\Models\ArticleFile
     */
    protected $model;

    /**
     * The Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\ArticleFile  $model
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(ArticleFile $model, Request $request)
    {
        $this->model = $model;

        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $articleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($articleId)
    {
        $data['foreignModels'] = (new Article)->where('id', $articleId)
            ->joinLanguage(false)
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($articleId)->paginate(20);

        return view('admin.collections.articles.files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $articleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function create($articleId)
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
     * @param  int  $articleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(FileRequest $request, $articleId)
    {
        $input = $request->all();
        $input['article_id'] = $articleId;

        $model = $this->model->create($input);

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
     * @param  int  $articleId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($articleId, $id)
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
     * @param  int  $articleId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(FileRequest $request, $articleId, $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

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
     * @param  int  $articleId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($articleId, $id)
    {
        $this->model->destroy($this->request->get('ids'));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Page\Page;
use App\Models\Page\PageFile;
use Illuminate\Http\Request;

class AdminPageFilesController extends Controller
{
    use Positionable, VisibilityTrait, LanguageRelationsTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PageFile $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $pageId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(int $pageId)
    {
        $data['foreignModels'] = (new Page)->where('id', $pageId)
            ->joinLanguage(false)
            ->joinCollection()
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($pageId)->paginate(24);

        // make a menu list active from this (files) resource by passing the
        // current page menu_id
        view()->composer('admin._partials.menu', function ($view) use ($data) {
            return $view->with('activeMenuId', $data['foreignModel']->menu_id);
        });

        return view('admin.pages.files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $pageId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(int $pageId)
    {
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;
            $data['current']->page_id = $pageId;

            return response()->json([
                'result' => true,
                'view' => view('admin.pages.files.create', $data)->render()
            ]);
        }

        return redirect(cms_route('pages.files.index', [$pageId]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  int  $pageId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, int $pageId)
    {
        $input = $request->all();
        $input['page_id'] = $pageId;

        $model = $this->model->create($input);

        $this->createLanguageRelations('languages', $input, $model->id, true);

        if ($request->expectsJson()) {
            $view = view('admin.pages.files.item', [
                'item' => $model,
                'itemInput' => $input
            ])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
            );
        }

        return redirect(cms_route('pages.files.index', [$pageId]));
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
     * @param  int  $pageId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(int $pageId, int $id)
    {
        if ($this->request->expectsJson()) {
            $data['items'] = $this->model->joinLanguage(false)
                ->where('id', $id)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view('admin.pages.files.edit', $data)->render()
            ]);
        }

        return redirect(cms_route('pages.files.index', [$pageId]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  int  $pageId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, int $pageId, int $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect(cms_route('pages.files.index', [$pageId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $pageId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $pageId, int $id)
    {
        $this->model->destroy($this->request->get('ids'));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

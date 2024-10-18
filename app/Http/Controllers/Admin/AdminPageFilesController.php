<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Support\Admin\AdminDestroy;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageFile;

class AdminPageFilesController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * The PageFile instance.
     *
     * @var \App\Models\PageFile
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
     * @param  \App\Models\PageFile  $model
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(PageFile $model, Request $request)
    {
        $this->model = $model;

        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $pageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($pageId)
    {
        $data['foreignModels'] = (new Page)->where('id', $pageId)
            ->joinLanguage(false)
            ->joinCollection()
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($pageId)->paginate(20);

        return view('admin.pages.files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $pageId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function create($pageId)
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(FileRequest $request, $pageId)
    {
        $input = $request->all();
        $input['page_id'] = $pageId;

        $model = $this->model->create($input);

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($pageId, $id)
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(FileRequest $request, $pageId, $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

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
     * @return mixed
     */
    public function destroy($pageId, $id)
    {
        $id = $this->request->get('ids');

        if (count($id) == 1) {
            $id = $id[0];
        }

        return (new AdminDestroy($this->model, $id))->handle();
    }
}

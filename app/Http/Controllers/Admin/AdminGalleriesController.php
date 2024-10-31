<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Collection;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryLanguage;
use Illuminate\Http\Request;

class AdminGalleriesController extends Controller
{
    use Positionable, VisibilityTrait, Transferable, ClonableLanguage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Gallery $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $collectionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(int $collectionId)
    {
        $data['parent'] = (new Collection)->where('type', Gallery::TYPE)
            ->findOrFail($collectionId);

        $data['items'] = $this->model->getAdminCollection($data['parent']);

        $data['parentSimilar'] = (new Collection)->byType($this->model::TYPE)->get();

        return view('admin.galleries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $collectionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, int $collectionId)
    {
        $data['current'] = $this->model;
        $data['current']->collection_id = $collectionId;
        $data['current']->type = $request->get('type');
        $data['current']->admin_per_page = 20;
        $data['current']->web_per_page = 10;

        return view('admin.galleries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\GalleryRequest  $request
     * @param  int  $collectionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GalleryRequest $request, int $collectionId)
    {
        $input = $request->all();
        $input['collection_id'] = $collectionId;

        $model = $this->model->create($input);

        $input['gallery_id'] = $model->id;

        $model->languages(false)->create($input);

        return redirect(cms_route('galleries.edit', [$collectionId, $model->id]))
            ->with('alert', fill_data('success', trans('general.created')));
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
     * @param  int  $collectionId
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $collectionId, int $id)
    {
        $data['items'] = $this->model->where('id', $id)
            ->forAdmin(null, false)
            ->getOrFail();

        $data['current'] = $data['items']->first();

        return view('admin.galleries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\GalleryRequest  $request
     * @param  int  $collectionId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(GalleryRequest $request, int $collectionId, int $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $languageModel = $this->model->languages(false)->byForeignLanguage($id)->first();

        ! is_null($languageModel)
            ? $languageModel->update($input)
            : $this->cloneLanguage($id, $input);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $collectionId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $collectionId, int $id)
    {
        $this->model->whereKey($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Collection;
use App\Models\Gallery\Gallery;
use Illuminate\Http\Request;

class AdminGalleriesController extends Controller
{
    use Positionable, VisibilityTrait, Transferable, LanguageRelationsTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Gallery $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $collectionId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $collectionId)
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
     * @param  string  $collectionId
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request, string $collectionId)
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
     * @param  string  $collectionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GalleryRequest $request, string $collectionId)
    {
        $input = $request->all();
        $input['collection_id'] = $collectionId;

        $model = $this->model->create($input);

        $this->createLanguageRelations('languages', $input, $model->id);

        return redirect(cms_route('galleries.edit', [$collectionId, $model->id]))
            ->with('alert', fill_data('success', trans('general.created')));
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

        return view('admin.galleries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\GalleryRequest  $request
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(GalleryRequest $request, string $collectionId, string $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

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
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $collectionId, string $id)
    {
        $this->model->whereKey($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

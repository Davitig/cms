<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Collection;
use App\Models\Menu;
use App\Models\Page\Page;
use Illuminate\Http\Request;

class AdminPagesController extends Controller
{
    use Positionable, VisibilityTrait, Transferable;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Page $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $menuId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $menuId)
    {
        $data['menu'] = (new Menu)->findOrFail($menuId);

        $data['items'] = make_sub_items($this->model->forAdmin($menuId)->get());

        return view('admin.pages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $menuId
     * @return \Illuminate\Contracts\View\View
     */
    public function create(string $menuId)
    {
        $data['current'] = $this->model;
        $data['current']->menu_id = $menuId;
        $data['current']->parent_id = $this->request->get('parent_id', 0);

        $data['types'] = cms_pages('types');

        $data['collectionTypes'] = [];

        return view('admin.pages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PageRequest  $request
     * @param  string  $menuId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageRequest $request, string $menuId)
    {
        $input = $request->all();
        $input['menu_id'] = $menuId;

        $model = $this->model->create($input);

        $model->languages()->createMany(apply_languages($input));

        return redirect(cms_route('pages.edit', [$menuId, $model->id]))
            ->with('alert', fill_data('success', trans('general.created')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $menuId
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $menuId, string $id)
    {
        $data['items'] = $this->model->whereKey($id)
            ->forAdmin(null, false)
            ->getOrFail();

        $data['current'] = $data['items']->first();

        $data['types'] = cms_pages('types');

        $data['collectionTypes'] = $this->getCollectionTypes($data['items']->first()->type);

        return view('admin.pages.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PageRequest $request
     * @param  string  $menuId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function update(PageRequest $request, string $menuId, string $id)
    {
        tap($this->model->findOrFail($id))
            ->update($input = $request->all())
            ->languages()
            ->updateOrCreate(apply_languages(), $input);

        if ($request->expectsJson()) {
            $type = $request->get('type');

            if (array_key_exists($type, (array) cms_pages('collections'))
                || array_key_exists($type, (array) cms_pages('extended'))) {
                $input['typeHtml'] = view(
                    'admin.pages._extended_type', ['input' => $input]
                )->render();
            }

            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $menuId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $menuId, string $id)
    {
        $this->deleteAndUpdateSubItems($id);

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }

    /**
     * Get the collection types.
     *
     * @param  string|null  $type
     * @return array
     */
    public function getCollectionTypes(?string $type = null)
    {
        if (! $type ??= $this->request->get('type')) {
            return [];
        }

        if (! cms_pages('collections.' . $type)) {
            return [];
        }

        return (new Collection)->byType($type)->pluck('title', 'id')->toArray();
    }

    /**
     * Collapse specified page.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function collapse()
    {
        if ($id = $this->request->get('id')) {
            $model = $this->model->findOrFail($id);

            if ($model->update(['collapse' => $model->collapse ? 0 : 1])) {
                return response()->json();
            }
        }

        return response(trans('general.invalid_input'), 422);
    }
}

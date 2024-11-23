<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Menu;
use App\Models\Page\Page;
use Illuminate\Http\Request;

class AdminPagesController extends Controller
{
    use Positionable, VisibilityTrait, Transferable, LanguageRelationsTrait;

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

        $data['listableTypes'] = [];

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

        $this->createLanguageRelations('languages', $input, $model->id, true);

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
        $data['items'] = $this->model->where('id', $id)
            ->forAdmin(null, false)
            ->getOrFail();

        $data['current'] = $data['items']->first();

        $data['types'] = cms_pages('types');

        $data['listableTypes'] = $this->getListableTypes($data['items']->first()->type);

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
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

        if ($request->expectsJson()) {
            if (array_key_exists(
                $type = $request->get('type'), (array) cms_pages('implicit')
            )) {
                $typeId = $request->get('type_id');

                $implicitModel = cms_pages('implicit.' . $type);

                if ($implicitModel) {
                    $implicitModelType = (new $implicitModel)->whereKey($typeId)->value('type');

                    $input['page_type'] = $implicitModelType;

                    $input['typeHtml'] = view(
                        'admin.pages._implicit_type', ['input' => $input]
                    )->render();
                }
            } elseif (array_key_exists(
                $request->get('type'), (array) cms_pages('explicit')
            )) {
                $input['typeHtml'] = view(
                    'admin.pages._module_type', ['input' => $input]
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
     * Get the listable types.
     *
     * @param  string|null  $type
     * @return array
     */
    public function getListableTypes(?string $type = null)
    {
        if (! $type && ! ($type = $this->request->get('type'))) {
            return [];
        }

        $model = cms_pages('implicit.' . $type) ?: cms_pages('explicit.' . $type);

        if (! $model) {
            return [];
        }

        if (method_exists($model = new $model, 'joinLanguage')) {
            $model = $model->joinLanguage();
        }

        return $model->pluck('title', 'id')->toArray();
    }

    /**
     * Get the templates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplates()
    {
        return response()->json(cms_pages('templates.' . $this->request->get('type')));
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
                return response()->json(true);
            }
        }

        return response(trans('general.invalid_input'), 422);
    }
}

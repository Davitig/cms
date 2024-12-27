<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Collection;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    use Positionable, VisibilityTrait, Transferable, LanguageRelationsTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Faq $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $collectionId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $collectionId)
    {
        $data['parent'] = (new Collection)->byType($this->model::TYPE)
            ->findOrFail($collectionId);

        $data['items'] = $this->model->getAdminCollection($data['parent']);

        $data['parentSimilar'] = (new Collection)->byType($this->model::TYPE)->get();

        return view('admin.collections.faq.index', $data);
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

        return view('admin.collections.faq.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FaqRequest  $request
     * @param  string  $collectionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FaqRequest $request, string $collectionId)
    {
        $input = $request->all();
        $input['collection_id'] = $collectionId;

        $model = $this->model->create($input);

        $this->createLanguageRelations('languages', $input, $model->id);

        return redirect(cms_route('faq.edit', [$collectionId, $model->id]))
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
        $data['items'] = $this->model->where('id', $id)
            ->forAdmin(null, false)
            ->getOrFail();

        $data['current'] = $data['items']->first();

        return view('admin.collections.faq.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FaqRequest  $request
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FaqRequest $request, string $collectionId, string $id)
    {
        $input = $request->all();

        $this->model->findOrFail($id)->update($input);

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

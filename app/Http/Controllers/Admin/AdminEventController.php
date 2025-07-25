<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Collection;
use App\Models\Event\Event;

class AdminEventController extends Controller
{
    use InteractsWithPosition, InteractsWithVisibility, Transferable;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Event $model) {}

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

        $data['similarCollections'] = (new Collection)->byType($this->model::TYPE)
            ->limit(50)->get()
            ->each(function ($item) {
                $item->count = $this->model->collectionId($item->id)->count();
            });

        $data['items'] = $this->model->filesExists()->getAdminCollection($data['parent']);

        return view('admin.collections.events.index', $data);
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

        return view('admin.collections.events.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\EventRequest $request
     * @param  string  $collectionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EventRequest $request, string $collectionId)
    {
        $input = $request->all();
        $input['collection_id'] = $collectionId;

        $model = $this->model->create($input);

        $model->languages()->createMany(apply_languages($input));

        return redirect(cms_route('events.edit', [$collectionId, $model->id]))
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

        return view('admin.collections.events.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\EventRequest  $request
     * @param  string  $collectionId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(EventRequest $request, string $collectionId, string $id)
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

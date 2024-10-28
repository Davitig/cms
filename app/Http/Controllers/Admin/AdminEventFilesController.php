<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Event\Event;
use App\Models\Event\EventFile;
use App\Models\Event\EventFileLanguage;
use Illuminate\Http\Request;

class AdminEventFilesController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EventFile $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $eventId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(int $eventId)
    {
        $data['foreignModels'] = (new Event)->where('id', $eventId)
            ->joinLanguage(false)
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($eventId)->paginate(20);

        return view('admin.collections.events.files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $eventId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(int $eventId)
    {
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;
            $data['current']->event_id = $eventId;

            return response()->json([
                'result' => true,
                'view' => view('admin.collections.events.files.create', $data)->render()
            ]);
        }

        return redirect(cms_route('events.files.index', [$eventId]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, int $eventId)
    {
        $input = $request->all();
        $input['event_id'] = $eventId;

        $model = $this->model->create($input);

        $input['event_file_id'] = $model->id;
        $model->languages(false)->create($input);

        if ($request->expectsJson()) {
            $view = view('admin.collections.events.files.item', [
                'item' => $model,
                'itemInput' => $input
            ])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
            );
        }

        return redirect(cms_route('events.files.index', [$eventId]));
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
     * @param  int  $eventId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(int $eventId, int $id)
    {
        if ($this->request->expectsJson()) {
            $data['items'] = $this->model->joinLanguage(false)
                ->where('id', $id)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view('admin.collections.events.files.edit', $data)->render()
            ]);
        }

        return redirect(cms_route('events.files.index', [$eventId]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  int  $eventId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, int $eventId, int $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $languageModel = (new EventFileLanguage)->byForeignLanguage($id)->first();

        if (! is_null($languageModel)) {
            $languageModel->update($input);
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect(cms_route('events.files.index', [$eventId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $eventId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $eventId, int $id)
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

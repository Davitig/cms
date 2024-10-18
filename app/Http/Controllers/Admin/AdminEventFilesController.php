<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Support\Admin\AdminDestroy;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventFile;

class AdminEventFilesController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * The EventFile instance.
     *
     * @var \App\Models\EventFile
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
     * @param  \App\Models\EventFile  $model
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(EventFile $model, Request $request)
    {
        $this->model = $model;

        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $eventId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($eventId)
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
     */
    public function create($eventId)
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(FileRequest $request, $eventId)
    {
        $input = $request->all();
        $input['event_id'] = $eventId;

        $model = $this->model->create($input);

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($eventId, $id)
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(FileRequest $request, $eventId, $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

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
     * @return mixed
     */
    public function destroy($eventId, $id)
    {
        $id = $this->request->get('ids');

        if (count($id) == 1) {
            $id = $id[0];
        }

        return (new AdminDestroy($this->model, $id))->handle();
    }
}

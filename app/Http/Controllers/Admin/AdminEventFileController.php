<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFileController as Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Event\Event;
use App\Models\Event\EventFile;
use Illuminate\Http\Request;

class AdminEventFileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(EventFile $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $eventId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $eventId)
    {
        return view('admin.collections.events.files.index', $this->indexData(
            $eventId, new Event
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $eventId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(string $eventId)
    {
        return $this->createData(
            $eventId,
            'admin.collections.events.files.create',
            cms_route('events.files.index', [$eventId])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $eventId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, string $eventId)
    {
        return $this->storeData(
            $request,
            $eventId,
            'admin.collections.events.files.item',
            cms_route('events.files.index', [$eventId])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $eventId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $eventId, string $id)
    {
        return $this->editData(
            $eventId,
            $id,
            'admin.collections.events.files.edit',
            cms_route('events.files.index', [$eventId])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $eventId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, string $eventId, string $id)
    {
        return $this->updateData($request, $eventId, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $eventId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $eventId, string $id)
    {
        return $this->destroyData($eventId, $id);
    }
}

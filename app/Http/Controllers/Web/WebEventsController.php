<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventFile;

class WebEventsController extends Controller
{
    /**
     * The Event instance.
     *
     * @var \App\Models\Event
     */
    protected $model;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Event  $model
     * @return void
     */
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page, \App\Models\Collection>  $models
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(array $models)
    {
        [$data['current'], $collection] = $models;

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.events', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page, \App\Models\Collection>  $models
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(array $models, $slug)
    {
        [$data['parent'], $collection] = $models;

        $data['current'] = $this->model->byCollectionSlug($collection->id, $slug)->firstOrFail();

        $data['files'] = (new EventFile)->getFiles($data['current']->id);

        return view('web.event', $data);
    }
}

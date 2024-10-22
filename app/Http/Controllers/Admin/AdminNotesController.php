<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Note;
use Illuminate\Http\Request;

class AdminNotesController extends Controller
{
    /**
     * The Note instance.
     *
     * @var \App\Models\Note
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
     * @param  \App\Models\Note  $model
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Note $model, Request $request)
    {
        $this->model = $model;

        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->orderDesc()->get();

        return view('admin.notes.index', $data);
    }

    /**
     * Create or update the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $input = $this->request->all(['title', 'description', 'content']);

        if ($this->request->filled('id')) {
            $id = $this->request->get('id');

            $this->model->findOrFail($id)->update($input);
        } else {
            $this->model = $this->model->create($input);
        }

        if ($this->request->expectsJson()) {
            return response()->json($this->model->id);
        }

        return redirect()->back();
    }

    /**
     * Move resource into the calendar.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function calendar(Calendar $calendar)
    {
        $input['title'] = $this->request->get('title');

        $content = explode(PHP_EOL, $this->request->get('content'));

        if (count($content) > 1) {
            array_shift($content);

            $input['description'] = implode(PHP_EOL, $content);
        }

        $input['color'] = $calendar->getRandomColor();

        $model = $calendar->create($input);

        if ($this->request->expectsJson()) {
            return response()->json($model);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $id = $this->request->get('id');

        $model = $this->model->delete($id);

        if ($this->request->expectsJson()) {
            return response()->json($model);
        }

        return redirect()->back();
    }
}

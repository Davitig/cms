<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Note;
use Illuminate\Http\Request;

class AdminNotesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Note $model, protected Request $request) {}

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

        $model = $this->model->whereKey($id)->delete();

        if ($this->request->expectsJson()) {
            return response()->json($model);
        }

        return redirect()->back();
    }
}

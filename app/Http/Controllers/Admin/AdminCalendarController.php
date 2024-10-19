<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;

class AdminCalendarController extends Controller
{
    /**
     * The Calendar instance.
     *
     * @var \App\Models\Calendar
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
     * @param  \App\Models\Calendar  $model
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Calendar $model, Request $request)
    {
        $this->model = $model;

        $this->request = $request;
    }

    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->getInactive();

        return view('admin.calendar.index', $data);
    }

    /**
     * Get all events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function events()
    {
        $date = $this->request->all(['start', 'end']);

        $data = $this->model->getActive($date['start'], $date['end']);

        return response()->json($data);
    }

    /**
     * Create or update the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $this->request->validate(['title' => 'required|min:2']);

        if ($this->request->filled('id')) {
            $id = $this->request->get('id');

            $this->model->findOrFail($id)->updateEvent($this->request);
        } else {
            $input = $this->request->all();
            $input['color'] = $this->model->getRandomColor();

            $this->model = $this->model->create($input);
        }

        if ($this->request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.saved'), $this->model->getAttributes()
            ));
        }

        return redirect()->back();
    }

    /**
     * Remove the specified calendar event.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $id = $this->request->get('id');

        $result = $this->model->delete($id);

        if ($this->request->expectsJson()) {
            return response()->json($result);
        }

        return redirect()->back();
    }
}

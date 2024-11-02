<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\Request;

class AdminCalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Calendar $model, protected Request $request) {}

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
        $this->request->validate(['title' => 'required|min:1']);

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

        return back();
    }

    /**
     * Remove the specified calendar event.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->model->whereKey($this->request->get('id'))->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

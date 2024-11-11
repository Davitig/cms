<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\Request;

class AdminCalendarController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Calendar $model, protected Request $request) {}

    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->byUserId($this->request->user('cms')->id)
            ->inactive()
            ->get();

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

        $data = $this->model->byUserId($this->request->user('cms')->id)
            ->active($date['start'], $date['end'])
            ->get();

        return response()->json($data);
    }

    /**
     * Create or update the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $this->request->validate(['title' => 'required']);

        if ($this->request->filled('id')) {
            $id = $this->request->get('id');

            $this->model->findOrFail($id)->updateEvent($this->request);
        } else {
            $input = $this->request->all();
            $input['color'] = $this->model->getRandomColor();
            $input['cms_user_id'] = $this->request->user('cms')->id;

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
        $this->model->byUserId($this->request->user('cms')->id)
            ->whereKey($this->request->get('id'))
            ->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

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
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->byUserId($this->request->user()->id)
            ->orderDesc()
            ->get();

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
            $input['cms_user_id'] = $this->request->user()->id;

            $this->model = $this->model->create($input);
        }

        if ($this->request->expectsJson()) {
            return response()->json($this->model->id);
        }

        return back();
    }

    /**
     * Remove the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $this->model->byUserId($this->request->user()->id)
            ->whereKey($this->request->get('id'))
            ->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

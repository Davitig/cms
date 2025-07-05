<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CollectionRequest;
use App\Models\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AdminCollectionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Collection $model) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->paginate(50);

        return view('admin.collections.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $data['current'] = $this->model;
        $data['current']->type = $request->get('type');
        $data['current']->admin_per_page = 50;
        $data['current']->web_per_page = 10;

        return view('admin.collections.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CollectionRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CollectionRequest $request)
    {
        $model = $this->model->create($request->all());

        return redirect(cms_route('collections.edit', [$model->id]))
            ->with('alert', fill_data(true, trans('general.created')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $data['current'] = $this->model->findOrFail($id);

        return view('admin.collections.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CollectionRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(CollectionRequest $request, string $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back()->with('alert', fill_data(true, trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $errorCode = 0;

        $data = fill_data(true, trans('database.deleted'));

        try {
            $this->model->findOrFail($id)->delete();
        } catch (QueryException $e) {
            $errorCode = (string) ($e->errorInfo[1] ?? null);

            $data = fill_data(
                (int) ! $errorCode,
                trans('database.' . ($errorCode ? 'error.' . $errorCode : 'deleted'))
            );
        }

        if (request()->expectsJson()) {
            return response()->json($data, $errorCode ? 403 : 200);
        }

        return back()->with('alert', $data);
    }

    /**
     * Get the delete response.
     *
     * @param  string|null  $errorCode
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function deleteResponse(?string $errorCode = null)
    {
        $message = trans('database.' . ($errorCode ? 'error.' . $errorCode : 'deleted'));

        if (request()->expectsJson()) {
            return response()->json(fill_data((int) ! $errorCode, $message), 403);
        }

        return back()->with('alert', fill_data((int) ! $errorCode, $message));
    }
}

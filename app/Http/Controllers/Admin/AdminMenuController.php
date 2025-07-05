<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Menu $model) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // menus' data is loaded from middleware

        return view('admin.menus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['current'] = $this->model;

        return view('admin.menus.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\MenuRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuRequest $request)
    {
        if ($request->boolean('main')) {
            $this->uncheckAllMain();
        }

        $model = $this->model->create($request->all());

        return redirect(cms_route('menus.edit', [$model->id]))
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

        return view('admin.menus.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\MenuRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, string $id)
    {
        if ($request->boolean('main')) {
            $this->uncheckAllMain();
        }

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
        $result = $this->model->findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data($result, trans('database.deleted')));
        }

        return back()->with('alert', fill_data($result, trans('database.deleted')));
    }

    /**
     * Update the specified resource "main" attribute in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateMain(Request $request)
    {
        if ($id = $request->get('id')) {
            $this->uncheckAllMain();

            return response()->json(fill_data(
                $this->model->findOrFail($id)->update(['main' => 1]),
                trans('general.updated')
            ));
        }

        return response(trans('general.invalid_input'), 422);
    }

    /**
     * Uncheck the "main" attribute from all the main-checked resources.
     *
     * @return bool
     */
    protected function uncheckAllMain(): bool
    {
        return $this->model->whereMain(1)->update(['main' => 0]);
    }
}

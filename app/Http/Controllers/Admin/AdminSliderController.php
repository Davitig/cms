<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFilesController as Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class AdminSliderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(Slider $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->forAdmin()->get();

        return view('admin.slider.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create()
    {
        return $this->createData('', 'admin.slider.create', cms_route('slider.index'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\SliderRequest  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(SliderRequest $request)
    {
        return $this->storeData(
            $request, '', 'admin.slider.item', cms_route('slider.index')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $id)
    {
        return $this->editData(
            '', $id, 'admin.slider.edit', cms_route('slider.index')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\SliderRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(SliderRequest $request, string $id)
    {
        return $this->updateData($request, '', $id, cms_route('slider.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        return $this->destroyData('', $id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use App\Models\SliderLanguage;
use Illuminate\Http\Request;

class AdminSliderController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Slider $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;

            return response()->json([
                'result' => true,
                'view' => view('admin.slider.create', $data)->render()
            ]);
        }

        return redirect(cms_route('slider.index'));
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
        $model = $this->model->create($input = $request->all());

        $input['slider_id'] = $model->id;
        $model->languages(false)->create($input);

        if ($request->expectsJson()) {
            $view = view('admin.slider.item', [
                'item' => $model,
                'itemInput' => $input
            ])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
            );
        }

        return redirect(cms_route('slider.index'));
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(int $id)
    {
        if ($this->request->expectsJson()) {
            $data['items'] = $this->model->where('id', $id)
                ->forAdmin(false)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view('admin.slider.edit', $data)->render()
            ]);
        }

        return redirect(cms_route('slider.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\SliderRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(SliderRequest $request, int $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $languageModel = (new SliderLanguage)->byForeign($id)->first();

        if (! is_null($languageModel)) {
            $languageModel->update($input);
        }

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect(cms_route('slider.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->model->destroy($this->request->get('ids'));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
    }
}

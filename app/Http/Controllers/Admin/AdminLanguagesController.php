<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;

class AdminLanguagesController extends Controller
{
    use Positionable;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Language  $model
     */
    public function __construct(protected Language $model) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->positionAsc()->get();

        return view('admin.languages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['current'] = $this->model;

        return view('admin.languages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\LanguageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LanguageRequest $request)
    {
        $model = $this->model->create($request->all());

        return redirect(cms_route('languages.edit', [$model->id]))
            ->with('alert', fill_data('success', trans('general.created')));
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
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $data['current'] = $this->model->findOrFail($id);

        return view('admin.languages.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\LanguageRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(LanguageRequest $request, string $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->model->destroy($id);

        $url = null;

        $language = language(true);

        $languages = languages();

        unset($languages[$language['language']]);

        if (language_in_url()) {
            if (count($languages) <= 1) {
                $url = cms_route('languages.index', [], false);
            } elseif ($language['id'] == $id) {
                $url = cms_route('languages.index', [], key($languages));
            }
        }

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted'), ['redirect' => $url]
            ));
        }

        $url ??= cms_route('languages.index');

        return redirect($url)->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
    }
}

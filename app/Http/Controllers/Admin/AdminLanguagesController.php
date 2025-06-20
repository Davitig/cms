<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class AdminLanguagesController extends Controller
{
    use Positionable, VisibilityTrait;

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
        $data['items'] = $this->model->positionAsc()->paginate(100);

        $data['hasVisibleLang'] = $this->model->whereVisible()->exists();

        $data['routesAreCached'] = app()->routesAreCached();

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
        if ($request->boolean('main')) {
            $this->uncheckAllMain();
        }

        $model = $this->model->create($request->all());

        return redirect(cms_route('languages.edit', [$model->id]))
            ->with('alert', fill_data('success', trans('general.created')));
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
        if ($request->boolean('main')) {
            $this->uncheckAllMain();
        }

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

        if (! is_null($language = language()->getActive())) {
            $languages = language()->all();

            unset($languages[$language['language']]);

            if (language()->isSelected()) {
                if (count($languages) <= 1) {
                    $url = cms_route('languages.index', [], false);
                } elseif ($language['id'] == $id) {
                    $url = cms_route('languages.index', [], key($languages));
                }
            }
        }

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted'), ['redirect' => $url]
            ));
        }

        return redirect($url ?: cms_route('languages.index'))->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
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

            return response()->json(
                $this->model->findOrFail($id)->update(['main' => 1])
            );
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

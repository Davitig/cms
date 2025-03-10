<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TranslationRequest;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminTranslationsController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Translation $model) {}

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return ['cms.withFullAccess'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->joinLanguage()->get();

        return view('admin.translations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data['current'] = $this->model;

        $data['transTypes'] = (array) cms_config('trans_types');

        return view('admin.translations.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\TranslationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TranslationRequest $request)
    {
        $model = $this->model->create($input = $request->all());

        $model->languages()->createMany(apply_languages($input));

        return redirect(cms_route('translations.edit', [$model->id]))
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
        $data['items'] = $this->model->joinLanguage(false)
            ->whereKey($id)
            ->getOrFail();

        $data['transTypes'] = (array) cms_config('trans_types');

        return view('admin.translations.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\TranslationRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(TranslationRequest $request, string $id)
    {
        tap($this->model->findOrFail($id))
            ->update($input = $request->except(['code']))
            ->languages()
            ->updateOrCreate(apply_languages(), $input);

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
        $this->model->whereKey($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }

    /**
     * Get the translation modal form by speicific code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getForm(Request $request)
    {
        if (! ($name = $request->get('code'))) {
            return response('Invalid code.', 422);
        }

        $data['items'] = $this->model->byCode($name, false)->get();

        if ($data['items']->isEmpty()) {
            $data['current'] = $this->model;
            $data['current']->code = $name;

            $form = 'create';
        } else {
            $form = 'edit';
        }

        $data['transTypes'] = (array) cms_config('trans_types');

        return view('admin.translations.modal.' . $form, $data);
    }

    /**
     * Create/Update a translation model.
     *
     * @param  \App\Http\Requests\Admin\TranslationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setData(TranslationRequest $request)
    {
        $input = $request->all('id', 'code', 'title', 'value', 'type');

        if (is_null($input['id'])) {
            $this->model->create($input)->languages()->createMany(apply_languages($input));
        } else {
            unset($input['code']);

            tap($this->model->findOrFail($input['id']))
                ->update($input)
                ->languages()
                ->updateOrCreate(apply_languages(), $input);
        }

        return response()->json($input);
    }
}

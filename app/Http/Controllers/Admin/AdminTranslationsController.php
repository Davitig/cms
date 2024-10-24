<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TranslationRequest;
use App\Models\Translation;
use App\Models\TranslationLanguage;
use Illuminate\Http\Request;

class AdminTranslationsController extends Controller
{
    use ClonableLanguage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Translation $model) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['items'] = $this->model->joinLanguage()->get();

        return view('admin.translations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

        $input['translation_id'] = $model->id;
        $model->languages(false)->create($input);

        return redirect(cms_route('translations.edit', [$model->id]))
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
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $data['items'] = $this->model->joinLanguage(false)
            ->where('id', $id)
            ->getOrFail();

        $data['transTypes'] = (array) cms_config('trans_types');

        return view('admin.translations.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\TranslationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(TranslationRequest $request, int $id)
    {
        $input = $request->all();

        $this->model->findOrFail($id)->update($input);

        $languageModel = (new TranslationLanguage)->byForeign($id)->first();

        ! is_null($languageModel)
            ? $languageModel->update($input)
            : $this->cloneLanguage($id, $input);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return back()->with('alert', fill_data(
            'success', trans('general.updated')
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->model->whereKey($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data('success', trans('database.deleted')));
        }

        return back()->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
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
            unset($input['id']);

            $this->model->create($input);
        } else {
            unset($input['code']);

            $model = $this->model->findOrFail($input['id']);

            $model->update($input);

            $input['code'] = $model->code;
        }

        return response()->json($input);
    }
}

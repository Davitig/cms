<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TranslationRequest;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminTranslationsController extends Controller implements HasMiddleware
{
    use LanguageRelationsTrait;

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

        $this->createLanguageRelations('languages', $input, $model->id, true);

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
            ->where('id', $id)
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
        $input = $request->all();
        unset($input['code']);

        $this->model->findOrFail($id)->update($input);

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

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
            $id = $this->model->create($input)->id;

            $this->createLanguageRelations('languages', $input, $id, true);
        } else {
            $model = $this->model->findOrFail($input['id']);

            unset($input['code']);

            $model->update($input);

            $this->updateOrCreateLanguageRelations('languages', $input, $input['id']);
        }

        return response()->json($input);
    }
}

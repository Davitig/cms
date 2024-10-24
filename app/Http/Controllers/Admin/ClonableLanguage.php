<?php

namespace App\Http\Controllers\Admin;

use App\Models\_Language;

trait ClonableLanguage
{
    /**
     * Store a cloned resource in storage.
     *
     * @param  int  $id
     * @param  array  $input
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function cloneLanguage(int $id, array $input = [])
    {
        $foreignId = $this->model->getForeignKey();
        $languageId = language(true, 'id');

        $langExists = $this->model->languages(false)->byForeign($id)->exists();

        if (! empty($input)) {
            $input[$foreignId] = $id;
            $input['language_id'] = $languageId;

            return $this->model->languages(false)->create($input);
        }

        $langModel = $this->model->languages(false)->where($foreignId, $id)->first();

        if ($langExists || is_null($langModel)) {
            if (request()->expectsJson()) {
                return response()->json();
            }

            return back();
        }

        $attributes = $langModel->getAttributes();
        $attributes['language_id'] = $languageId;

        $langModel->create($attributes);

        return back()->with('alert', fill_data('success', trans('general.created')));
    }
}

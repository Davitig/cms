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
        $currentLangExists = $this->model->languages(false)->byForeignLanguage($id)->exists();

        if ($currentLangExists) {
            return $this->cloneResponse();
        }

        $languageId = language(true, 'id');

        if (! empty($input)) {
            $input[$this->model->getForeignKey()] = $id;
            $input['language_id'] = $languageId;

            $this->model->languages(false)->create($input);

            return $this->cloneResponse($input);
        }

        $langModel = $this->model->languages(false)->foreignId($id)->first();

        if (is_null($langModel)) {
            return $this->cloneResponse();
        }

        $attributes = $langModel->getAttributes();
        $attributes['language_id'] = $languageId;

        $langModel->create($attributes);

        return $this->cloneResponse($attributes);
    }

    /**
     * Get clone response.
     *
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function cloneResponse(array $data = [])
    {
        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $data
            ));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\_Language;

trait ClonableLanguage
{
    /**
     * Store a cloned resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cloneLanguage(int $id)
    {
        $foreignId = $this->model->getForeignKey();

        $langExists = $this->model->languages(false)->where($foreignId, $id)
            ->where('language_id', $languageId = language(true, 'id'))
            ->exists();

        $langModel = $this->model->languages(false)->where($foreignId, $id)->first();

        if ($langExists || is_null($langModel)) {
            return redirect()->back();
        }

        $attributes = $langModel->getAttributes();
        $attributes['language_id'] = $languageId;

        $langModel->create($attributes);

        return redirect()->back()->with('alert', fill_data('success', trans('general.created')));
    }
}

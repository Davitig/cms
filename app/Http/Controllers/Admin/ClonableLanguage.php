<?php

namespace App\Http\Controllers\Admin;

use App\Models\_Language;
use App\Models\Page;

trait ClonableLanguage
{
    /**
     * Store a cloned resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cloneLanguage($id)
    {
        $_languageModel = (new _Language($this->model));

        $foreignId = str($this->model->getTable())->singular() . '_id';

        $currentLangExists = $_languageModel->where($foreignId, $id)
            ->where('language_id', $languageId = language(true, 'id'))
            ->exists();

        $currentLangModel = $_languageModel->where($foreignId, $id)->first();

        if ($currentLangExists || is_null($currentLangModel)) {
            return redirect()->back();
        }

        $attributes = $currentLangModel->getRefillAttributes($_languageModel->getFillable());
        $attributes['language_id'] = $languageId;

        $_languageModel::unguard();
        $_languageModel->create($attributes);

        return redirect()->back()->with('alert', fill_data('success', trans('general.created')));
    }
}

<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LanguageTrait
{
    /**
     * Build a query by foreign model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByForeignLanguage(Builder $query, int $id, mixed $currentLang = true): Builder
    {
        return $query->foreignKey($id)->whereLanguage($currentLang);
    }

    /**
     * Add a where "language_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereLanguage(Builder $query, mixed $currentLang = true): Builder
    {
        return $query->where(
            'language_id',
            is_numeric($currentLang) ? $currentLang : language($currentLang, 'id')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (isset($attributes['language_id'])) {
            return parent::create($attributes);
        }

        $models = [];

        foreach(languages() as $value) {
            $attributes['language_id'] = $value['id'];

            $models[$attributes['language_id']] = parent::create($attributes);
        }

        return $models;
    }
}

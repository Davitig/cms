<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LanguageTrait
{
    /**
     * Add a where "foreign_key" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function scopeForeignKey(Builder $query, int $foreignKey): Builder;

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
            is_numeric($currentLang) ? $currentLang : language()->getBy($currentLang, 'id')
        );
    }
}

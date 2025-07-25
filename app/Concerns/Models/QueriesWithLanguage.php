<?php

namespace App\Concerns\Models;

use Illuminate\Database\Eloquent\Builder;

trait QueriesWithLanguage
{
    /**
     * Add a where language foreign key clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder;

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
        return $query->languageForeignKey($id)->whereLanguage($currentLang);
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

<?php

namespace App\Concerns\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait QueriesLanguageRelationship
{
    /**
     * Language one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    abstract public function languages(): HasMany;

    /**
     * Add a languages cross join to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCrossMainLanguages(Builder $query): Builder
    {
        return $query->crossJoin('languages');
    }

    /**
     * Add a "*_languages" join to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinLanguage(
        Builder $query, mixed $currentLang = true, array|string $columns = [], string $type = 'left'
    ): Builder
    {
        $langIsEmpty = language()->isEmpty();

        $table = $this->getTable();
        $languageTable = $this->languages()->getRelated()->getTable();
        $join = ($langIsEmpty ? 'left' : $type) . 'Join';

        return $query->when($currentLang === false && ! $langIsEmpty, function ($q) {
            return $q->crossMainLanguages()->orderBy('languages.position');
        }, function ($q) use ($currentLang, $langIsEmpty) {
            return $q->when(! $langIsEmpty, function ($q) use ($currentLang) {
                return $q->leftJoin('languages', function ($q) use ($currentLang) {
                    return $q->when($this->wrapWhereLanguageQuery('languages.id', $currentLang));
                })->when(! cms_booted(), fn ($q) => $q->where('languages.visible', 1));
            });
        })->$join($languageTable, function ($q) use ($table, $languageTable, $langIsEmpty) {
            return $q->on("{$table}.id", "{$languageTable}.{$this->getForeignKey()}")
                ->when(! $langIsEmpty, function ($q) use ($languageTable) {
                    return $q->whereColumn($languageTable . '.language_id', 'languages.id');
                });
        })->addSelect(((array) $columns) ?: ["{$languageTable}.*", "{$table}.*"])
            ->when(! $langIsEmpty, function ($q) {
                return $q->addSelect(['languages.language']);
            });
    }

    /**
     * Apply the where language callback to the query.
     *
     * @param  string  $column
     * @param  mixed  $currentLang
     * @return \Closure
     */
    protected function wrapWhereLanguageQuery(string $column, mixed $currentLang = true): Closure
    {
        return function ($q) use ($column, $currentLang) {
            return $q->where(
                $column, is_numeric($currentLang)
                ? $currentLang
                : language()->getBy($currentLang, 'id')
            );
        };
    }
}

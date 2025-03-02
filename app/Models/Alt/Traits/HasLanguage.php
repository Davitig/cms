<?php

namespace App\Models\Alt\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasLanguage
{
    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Model
     */
    abstract public function languages(bool $relation = true): HasMany|Model;

    /**
     * Merge the language attributes into the model.
     *
     * @param  array  $attributes
     * @return static
     */
    public function mergeLanguageAttributes(array $attributes): static
    {
        $attributes = array_intersect_key(
            $attributes, array_flip($this->languages(false)->getFillable())
        );

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinLanguage(
        Builder $query, mixed $currentLang = true, array|string $columns = []
    ): Builder
    {
        $table = $this->getTable();
        $languageTable = $this->languages(false)->getTable();

        return $query->when($currentLang === false, function ($q) {
            return $q->crossMainLanguages()->orderBy('languages.position');
        }, function ($q) use ($currentLang) {
            return $q->leftJoin('languages', function ($q) use ($currentLang) {
                return $q->when($this->wrapWhereLanguageQuery('languages.id', $currentLang));
            })->when(! cms_activated(), fn ($q) => $q->where('languages.visible', 1));
        })->leftJoin($languageTable, function ($q) use ($table, $languageTable) {
            return $q->on("{$table}.id", "{$languageTable}.{$this->getForeignKey()}")
                ->whereColumn($languageTable . '.language_id', 'languages.id');
        })->addSelect(array_merge(((array) $columns) ?: ["{$languageTable}.*"], [
            "{$table}.*"
        ]))->addSelect(['languages.language', 'languages.id as language_id']);
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
                $column, is_numeric($currentLang) ? $currentLang : language($currentLang, 'id')
            );
        };
    }
}

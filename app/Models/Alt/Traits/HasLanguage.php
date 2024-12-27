<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use Closure;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait HasLanguage
{
    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Alt\Eloquent\Model
     */
    abstract public function languages(bool $relation = true): HasMany|Model;

    /**
     * Add a languages cross join to the query.
     *
     * @return \App\Models\Alt\Eloquent\Builder|\App\Models\Alt\Eloquent\Model
     */
    public function crossMainLanguages(): Builder|Model
    {
        return $this->crossJoin('languages');
    }

    /**
     * Add a "*_languages" join to the query.
     *
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function joinLanguage(mixed $currentLang = true, array|string $columns = []): Builder|static
    {
        $table = $this->getTable();
        $languageTable = $this->languages(false)->getTable();

        return $this->when($currentLang === false, function ($q) {
            return $q->crossMainLanguages()->orderBy('languages.position');
        }, function ($q) use ($currentLang) {
            return $q->leftJoin('languages', function ($q) use ($currentLang) {
                return $q->when($this->wrapWhereLanguageQuery('languages.id', $currentLang));
            });
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
                $column, is_int($currentLang) ? $currentLang : language($currentLang, 'id')
            );
        };
    }
}

<?php

namespace App\Models\Traits;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait HasLanguage
{
    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Base\Model
     */
    abstract public function languages(bool $relation = true): HasMany|Model;

    /**
     * Add a languages cross join to the query.
     *
     * @return \App\Models\Base\Builder
     */
    public function crossMainLanguages(): Builder
    {
        return $this->crossJoin('languages');
    }

    /**
     * Add a "*_languages" join to the query.
     *
     * @param  bool|string  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Base\Builder
     */
    public function joinLanguage(bool|string $currentLang = true, array|string $columns = []): Builder
    {
        $table = $this->getTable();
        $languageTable = $this->languages()->getRelated()->getTable();
        $languageKey = Str::singular($languageTable) . '_id';

        return $this->when($currentLang === false, function ($q) {
            return $q->crossMainLanguages()->orderBy('languages.main')->orderBy('languages.id');
        }, function ($q) use ($currentLang) {
            return $q->leftJoin('languages', function ($q) use ($currentLang) {
                return $q->where(
                    'languages.id', is_numeric($currentLang) ? $currentLang : language($currentLang, 'id')
                );
            });
        })->leftJoin($languageTable, function ($q) use ($table, $languageTable) {
            return $q->on("{$table}.id", "{$languageTable}.{$this->getForeignKey()}")
                ->whereColumn($languageTable . '.language_id', 'languages.id');
        })->addSelect(array_merge(((array) $columns) ?: ["{$languageTable}.*"], [
            "{$languageTable}.id as {$languageKey}", "{$table}.*"
        ]))->addSelect(['languages.language', 'languages.id as language_id']);
    }
}

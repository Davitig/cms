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
     * Add a "*_languages" join to the query.
     *
     * @param  bool|string  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Base\Builder
     */
    public function joinLanguage(bool|string $currentLang = true, array|string $columns = []):
    Builder
    {
        $table = $this->getTable();
        $languageTable = $this->languages()->getRelated()->getTable();
        $languageKey = Str::singular($languageTable) . '_id';

        return $this->leftJoin($languageTable,
            function ($q) use ($table, $languageTable, $currentLang) {
                return $q->on("{$table}.id", "{$languageTable}.{$this->getForeignKey()}")
                    ->when($currentLang !== false, function ($q) use ($languageTable, $currentLang) {
                        return $q->where(
                            "{$languageTable}.language_id",
                            is_numeric($currentLang) ? $currentLang : language($currentLang, 'id')
                        );
                    });
            })->addSelect(array_merge(((array) $columns) ?: ["{$languageTable}.*"], [
            "{$languageTable}.id as {$languageKey}", "{$table}.*"
        ]))->selectSub(function ($q) use ($languageTable) {
            return $q->from('languages')
                ->whereColumn('languages.id', $languageTable . '.language_id')
                ->select('language');
        }, 'language');
    }
}

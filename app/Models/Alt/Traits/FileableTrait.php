<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Eloquent\Builder;

trait FileableTrait
{
    /**
     * Add a files count to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeCountFiles(Builder $query): Builder
    {
        return $query->selectSub(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId)
                ->selectRaw('count(*)');
        }, 'files_count');
    }

    /**
     * Determine if the model has a file(s).
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeFilesExists(Builder $query): Builder
    {
        return $query->selectExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'files_exists');
    }

    /**
     * Add a query where a file exists or not.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  bool  $exists
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeWhereFileExists(Builder $query, bool $exists = true): Builder
    {
        return $query->whereExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'and', ! $exists);
    }
}

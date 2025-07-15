<?php

namespace App\Concerns\Models;

use Illuminate\Database\Eloquent\Builder;

trait Fileable
{
    /**
     * Add a files count to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCountFiles(Builder $query): Builder
    {
        return $query->selectSub(function ($q) {
            $keyName = $this->getKeyName();
            $tableId = ($table = $this->getTable()).'.'.$keyName;

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_' . $keyName, $tableId)
                ->selectRaw('count(*)');
        }, 'files_count');
    }

    /**
     * Add a select files exists statement to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilesExists(Builder $query): Builder
    {
        return $query->selectExists(
            ($table = str($this->getTable())->singular()) . '_files',
            $table . '_' . $this->getKeyName(),
            'files_exists'
        );
    }

    /**
     * Add a query where a file exists or not.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $exists
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFileExists(Builder $query, bool $exists = true): Builder
    {
        return $query->whereExists(function ($q) {
            $keyName = $this->getKeyName();
            $tableId = ($table = $this->getTable()).'.'.$keyName;

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_' . $keyName, $tableId);
        }, 'and', ! $exists);
    }
}

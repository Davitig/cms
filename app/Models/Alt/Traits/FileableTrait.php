<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Eloquent\Builder;

trait FileableTrait
{
    /**
     * Add a files count to the query.
     *
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function countFiles(): Builder|static
    {
        return $this->selectSub(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId)
                ->selectRaw('count(*)');
        }, 'files_count');
    }

    /**
     * Determine if the model has a file(s).
     *
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function filesExists(): Builder
    {
        return $this->selectExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'files_exists');
    }

    /**
     * Add a query where a file exists or not.
     *
     * @param  bool  $exists
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function whereFileExists(bool $exists = true): Builder|static
    {
        return $this->whereExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'and', ! $exists);
    }
}

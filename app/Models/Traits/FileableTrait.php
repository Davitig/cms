<?php

namespace App\Models\Traits;

use App\Models\Base\Builder;

trait FileableTrait
{
    /**
     * Add a files count to the query.
     *
     * @return \App\Models\Base\Builder
     */
    public function countFiles(): Builder
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
    * @return \App\Models\Base\Builder
     */
    public function hasFile(): Builder
    {
        return $this->selectExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'has_file');
    }

    /**
     * Add a query where a file exists or not.
     *
     * @param  bool  $exists
     * @return \App\Models\Base\Builder
     */
    public function whereFileExists($exists = true): Builder
    {
        return $this->whereExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'and', ! $exists);
    }
}

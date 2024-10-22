<?php

namespace App\Models\Traits;

use App\Models\File;

trait FileableTrait
{
    /**
     * Add a files count to the query.
     *
     * @return \App\Models\Eloquent\Builder
     */
    public function countFiles()
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
    * @return \App\Models\Eloquent\Builder
     */
    public function hasFile()
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
     * @return \App\Models\Eloquent\Builder
     */
    public function whereFileExists($exists = true)
    {
        return $this->whereExists(function ($q) {
            $tableId = ($table = $this->getTable()).'.'.$this->getKeyName();

            return $q->from(($table = str($table)->singular()) . '_files')
                ->whereColumn($table . '_id', $tableId);
        }, 'and', ! $exists);
    }
}

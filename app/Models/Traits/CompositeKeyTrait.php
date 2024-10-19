<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CompositeKeyTrait
{
    /**
     * {@inheritDoc}
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        if (empty($this->compositePrimaryKey)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ((array) $this->compositePrimaryKey as $key) {
            $query->where($key, $this->$key);
        }

        return $query;
    }
}

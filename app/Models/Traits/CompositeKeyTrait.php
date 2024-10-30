<?php

namespace App\Models\Traits;

trait CompositeKeyTrait
{
    /**
     * {@inheritDoc}
     */
    protected function setKeysForSelectQuery($query)
    {
        if (empty($this->compositePrimaryKey)) {
            return parent::setKeysForSelectQuery($query);
        }

        foreach ((array) $this->compositePrimaryKey as $key) {
            $query->where($key, $this->$key);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function setKeysForSaveQuery($query)
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

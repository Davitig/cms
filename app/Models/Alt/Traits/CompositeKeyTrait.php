<?php

namespace App\Models\Alt\Traits;

trait CompositeKeyTrait
{
    /**
     * {@inheritDoc}
     */
    protected function setKeysForSelectQuery($query)
    {
        if (empty($this->compositePrimaryKeys)) {
            return parent::setKeysForSelectQuery($query);
        }

        foreach ((array) $this->compositePrimaryKeys as $key) {
            $query->where($key, $this->$key);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function setKeysForSaveQuery($query)
    {
        if (empty($this->compositePrimaryKeys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ((array) $this->compositePrimaryKeys as $key) {
            $query->where($key, $this->$key);
        }

        return $query;
    }
}

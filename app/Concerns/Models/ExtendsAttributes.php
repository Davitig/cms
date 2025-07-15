<?php

namespace App\Concerns\Models;

trait ExtendsAttributes
{
    /**
     * Merge the array of model attributes without checking.
     *
     * @param  array  $attributes
     * @return static
     */
    public function mergeRawAttributes(array $attributes): static
    {
        $this->setRawAttributes(array_merge($attributes, $this->getAttributes()));

        return $this;
    }

    /**
     * Get the fillable attributes for the model, including statically defined columns.
     *
     * @param  bool  $qualifyColumns
     * @return array<string>
     */
    public function getFillableWithStaticColumns(bool $qualifyColumns = false): array
    {
        $fillable = array_merge(
            [$this->getKeyName()], $this->getFillable(), $this->getDates()
        );

        if (! $qualifyColumns) {
            return $fillable;
        }

        return $this->qualifyColumns($fillable);
    }
}
